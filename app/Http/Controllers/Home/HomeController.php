<?php

namespace App\Http\Controllers\Home;

use App\Exceptions\ErrorException;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Article;
use App\Models\Game;
use App\Models\M\Beizhu;
use App\Models\Packet;
use App\Models\Qiang;
use App\Models\Recharge;
use App\Models\Room;
use App\Models\Setting;
use App\Models\UserBalanceLog;
use App\Models\Withdraw;
use App\Services\BalanceService;
use App\Services\GameService;
use App\Services\Ser\PushService;
use App\Tools\Output;
use App\Tools\Pay\PayService;
use App\Tools\Push\Push;
use App\Tools\Sms\Wangyi;
use App\Tools\Vendor\Easemob;
use App\Tools\Vendor\Pinyin;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class HomeController extends Controller
{
    protected $gameService;

    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    public function index()
    {
        $rooms = $this->gameService->getRooms();
        $sum = $rooms->sum('people');
        $config = Setting::firstOrNew(['key' => 'gg']);
        return view('home.home')
            ->with('sum', $sum)
            ->with('gg', $config->value)
            ->with('user', Auth::user());
    }


    public function roomList2(Request $request)
    {
        $rooms = $this->gameService->getRooms2();
        $sum = $rooms->sum('people');
        return view('packet.roomList2')
            ->with('user', Auth::user())
            ->with('sum', $sum)
            ->with('rooms', $rooms);
    }

    public function roomList(Request $request)
    {
        $rooms = $this->gameService->getRooms();
        $sum = $rooms->sum('people');
        return view('packet.roomList')
            ->with('user', Auth::user())
            ->with('sum', $sum)
            ->with('rooms', $rooms);
    }

    public function roomPage(Request $request)
    {
        $id = $request->get('id');
        $room = Room::findOrFail($id);
        $user = Auth::user();
        $user->room_id = $room->id;
        $user->active_time = time();
        $user->save();
        return view('home.room')
            ->with('room', $room)
            ->with('user', $user);
    }
    public function pass(Request $request)
    {
        $id = $request->get('id');
        $room = Room::findOrFail($id);
        $pass = $request->get('pass');
        if($pass==$room->password){
            return Output::Success('');
        }
        return Output::Fail('密码错误');
    }

    //房间红包记录
    public function packetRecord(Request $request)
    {
        //开始id,从这个id开始读取数据，如果未0，从最近10条开始取
        $begin_id = $request->get('begin_id', 0);
        $room_id = $request->get('room_id');
        $user_id = Auth::id();
        if ($begin_id <= 0) {
            $r = Packet::where('room_id', $room_id)
                ->orderBy('id', 'desc')
                ->limit(15)
                ->get();
            foreach ($r as $item) {
                $begin_id = $item->id;
            }
            $begin_id--;
        }


        $list = Packet::with('user')->where('room_id', $room_id)
            ->where('id', '>', $begin_id)
            ->orderBy('id', 'asc')
            ->get();
        $packet_ids = $list->pluck('id')->toArray();
        if (count($packet_ids) > 0) {
            $qiang = Qiang::where('user_id', $user_id)
                ->whereIn('packet_id', $packet_ids)
                ->select('id', 'packet_id', 'money')
                ->get()
                ->keyBy('packet_id')
                ->toArray();

            foreach ($list as $packet) {
                if (array_key_exists($packet->id, $qiang)) {
                    $packet->qiang = 1;
                }
            }
        }
        return Output::Success('', [
            'list' => $list,
            'begin_id' => $list->max('id'),
            'balance_str' => Auth::user()->balance_str,
            'systemUser' => GameService::systemUser(),
        ]);
    }

    //查询抢红包状态
    public function checkGetPacketStatus(Request $request)
    {
        $user_id = Auth::id();
        $packet_id = $request->get('packet_id');
        $packet = Packet::findOrFail($packet_id);
        $qiang = Qiang::where('user_id', $user_id)
            ->where('money', '>', 0)
            ->where('packet_id', $packet_id)
            ->first();
        if (is_null($qiang) && $packet->can_get) {
            return Output::Success('', ['status' => 1, 'is_owner' => $packet->user_id == $user_id]);
        } else {
            $status = "已领取{$packet->got}/{$packet->packets}个，共{$packet->got_money_str}/{$packet->money_str}元";
            return Output::Success('', [
                'status' => 0,
                'owner' => $packet->user ? $packet->user : GameService::systemUser(),
                'get' => $qiang,
                'others' => Qiang::with('user')->where('money', '>', 0)->where('packet_id', $packet_id)->get(),
                'status' => $status,
            ]);
        }
    }

    //发红包
    public function sendPacket(Request $request)
    {
        $user_id = Auth::id();
        $money = intval($request->get('money') * 100);
        $room_id = $request->get('room_id');
        $lei = $request->get('lei');
        $msg = $request->get('msg');
        $packets = $request->get('packets');
        if ($packets < 5 || $packets > 10) {
            return Output::Fail('包数5-10');
        }
        return $this->gameService->putPacket($room_id, $user_id, $money, $lei, $packets, $msg);
    }

    //抢红包
    public function getPacket(Request $request)
    {
        $user_id = Auth::id();
        $packet_id = $request->get('packet_id');
        $packet = Packet::findOrFail($packet_id);
        if ($request->get('test')) {
            $status = "已领取{$packet->got}/{$packet->packets}个，共{$packet->got_money_str}/{$packet->money_str}元";
            $result = Output::Success('', [
                'status' => 0,
                'owner' => $packet->user ? $packet->user : GameService::systemUser(),
                'get' => '',
                'others' => Qiang::with('user')->where('money', '>', 0)->where('packet_id', $packet_id)->get(),
                'status' => $status,
            ]);
            return $result;
        }

        $result = $this->gameService->getPacket($user_id, $packet_id);
        $packet = Packet::findOrFail($packet_id);
        if ($result['errCode'] == 0) {

            $status = "已领取{$packet->got}/{$packet->packets}个，共{$packet->got_money_str}/{$packet->money_str}元";
            $result = Output::Success('', [
                'status' => 0,
                'owner' => $packet->user ? $packet->user : GameService::systemUser(),
                'get' => $result['data'],
                'others' => Qiang::with('user')->where('money', '>', 0)->where('packet_id', $packet_id)->get(),
                'status' => $status,
            ]);
        }
        return $result;
    }

    //余额日志
    public function balanceLog(Request $request)
    {
        $user_id = Auth::id();
        $list = UserBalanceLog::where('user_id', $user_id)->orderBy('id', 'desc')->paginate(20);
        $list->appends($request->except('page', 'per_page'));
        if ($request->ajax()) {
            return Output::Success('', $list);
        } else {
            return view('home.balanceLog')->with('list', $list)->with('user', Auth::user());
        }
    }


    public function user(Request $request)
    {
        $user = Auth::user();

        $str = date('Y-m-d 03:00:00', time());
        $h = date('h', time());
        $t = Carbon::createFromFormat('Y-m-d H:i:s', $str);
        if ($h >= 3) {
            $t2 = Carbon::createFromFormat('Y-m-d H:i:s', $str)->addDays(1);
        } else {
            $t2 = Carbon::createFromFormat('Y-m-d H:i:s', $str)->subDay(1);
        }
        $user->packet = Packet::where('user_id', $user->id)->whereBetween('created_at', [$t, $t2])->count();
        $user->packet_money = Packet::where('user_id', $user->id)->whereBetween('created_at', [$t, $t2])->sum('packet_money') / 100;

        return view('home.user')
            ->with('user', $user);
    }

    public function kf(Request $request)
    {
        $config = Setting::firstOrNew(['key' => 'kf']);
        return view('home.kf')->with('config', $config);
    }

    public function gz()
    {
        return view('home.gz');
    }

    //统计
    public function tongji(Request $request)
    {
        $user_id = Auth::id();
        $uid = $request->get('uid', $user_id);
        $day = $request->get('day', 0);
        $daySpan = strtotime('-' . $day . ' day', time());
        $day_str = date('Y-m-d', $daySpan);
        $dayInt = date('Ymd', $daySpan);
        $baobiao = $this->gameService->getConsumeRecord($uid, $dayInt);
        return view('home.tongji')
            ->with('user', Auth::user())
            ->with('list', $baobiao)
            ->with('former', '/tongji?uid=' . $uid . '&day=' . ($day + 1))
            ->with('next', $day - 1 < 0 ? '' : '/tongji?uid=' . $uid . '&day=' . max(0, ($day - 1)))
            ->with('day_str', $day_str);
    }

    //下线
    public function proxy(Request $request)
    {
        $user_id = Auth::id();
        $list = $this->gameService->getSubUserList($request, $user_id);
        $qrCodeUrl = asset('/?uid=' . $user_id);
        return view('home.xiaxian')
            ->with('list', $list)
            ->with('user', Auth::user())
            ->with('qrCodeUrl', $qrCodeUrl);
    }

    public function up(Request $request)
    {
        if ($request->isMethod('POST')) {
            $this->validate($request, [
                'money' => 'required|numeric|min:0.01',
                'pay_way' => 'required|in:wxpay,alipay',
            ], [
                'money.required' => '金额不能为空',
                'money.numeric' => '金额错误',
                'money.min' => '金额错误',
                'pay_way.required' => '支付方式不能为空',
                'pay_way.in' => '支付方式错误',
            ]);
            $charge = new Recharge();
            $charge->user_id = Auth::id();
            $charge->money = abs(intval($request->get('money') * 100));
            $charge->pay_way = $request->get('pay_way');
            $charge->state = 1;
            $charge->pay_at = Carbon::now();
            $charge->order_no = '10' . date('YmdHis', time()) . mt_rand(1000, 9999);
            $charge->day = date('Ymd', time());
            $result = $charge->save();
            return Output::Result($result, '提交成功', '提交失败');
        } else {
            $payWx = Setting::firstOrNew(['key' => 'payWx']);
            $payAli = Setting::firstOrNew(['key' => 'payAli']);
            $zfb_url = $payAli->value;
            $wx_url = $payWx->value;
            return view('home.up')
                ->with('zfb_url', $zfb_url)
                ->with('wx_url', $wx_url);
        }

    }

    public function down(Request $request)
    {
        if ($request->isMethod('POST')) {
            $this->validate($request, [
                'money' => 'required|numeric|min:0.01',
                'pay_way' => 'required|in:wxpay,alipay',
                'sk' => 'required',
                //'account' => 'required',
            ], [
                'money.required' => '金额不能为空',
                'money.numeric' => '金额错误',
                'money.min' => '金额错误',
                'pay_way.required' => '支付方式不能为空',
                'pay_way.in' => '支付方式错误',
                'account.required' => '账号不能为空',
                'sk.required' => '收款二维码不能为空',
            ]);

            $user = Auth::user();
            $user_id = Auth::id();

            $str = date('Y-m-d', time()) . ' 03:00:00';
            $h = date('h', time());
            $t = Carbon::createFromFormat('Y-m-d H:i:s', $str);
            if ($h >= 3) {
                $t2 = Carbon::createFromFormat('Y-m-d H:i:s', $str)->addDays(1);
            } else {
                $t2 = Carbon::createFromFormat('Y-m-d H:i:s', $str)->subDay(1);
            }

            $count = Withdraw::where('user_id', $user_id)
                ->whereBetween('created_at', [$t, $t2])
                ->count();
            if ($count >= 8) {
                return Output::Fail('每天下分最多8次');
            }
            $money = abs(intval($request->get('money') * 100));
            $account = $request->get('account');
            $account_type = $request->get('pay_way');
            $sk = $request->get('sk');

            if ($user->balance < $money) {
                return Output::Fail('余额不足');
            }

            $balanceService = new BalanceService();
            return $balanceService->requestWithdraw($user_id, $money, $account, $account_type, $sk);
        } else {
            return view('home.down')->with('user', Auth::user());
        }
    }

    public function openProxy(Request $request)
    {
        $uid = $request->get('uid');
        $user = User::findOrFail($uid);

        $i = Auth::user();
        if ($i->proxy > 0 && $i->proxy < 3) {
            $user->proxy = $i->proxy + 1;
        }
        return Output::Result($user->save(), '开启成功', '开启失败');
    }

    public function lq(Request $request)
    {
        if ($request->isMethod('POST')) {
            $qid = $request->get('qid');
            $user_id = Auth::id();
            $qiang = Qiang::where('user_id', $user_id)->findOrFail($qid);
            if ($qiang->reward != 1 || $qiang->get != 0) {
                return Output::Fail('没有可以领取的奖励');
            }
            try {
                DB::beginTransaction();
                $update = Qiang::where('user_id', $user_id)
                    ->where('reward', 1)
                    ->where('get', 0)
                    ->where('id', $qid)
                    ->update(
                        [
                            'get' => 1
                        ]
                    );
                if (!$update) {
                    throw new \Exception('');
                }
                $money = 1888;
                if ($money > 999) {
                    $money = 8888;
                }
                if ($money > 99999) {
                    $money = 28888;
                }
                $balanceService = new BalanceService();
                $balanceService->Back($user_id, $money, '豹子奖励');
                DB::commit();
                return Output::Success('领取成功');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error($e);
            }
            return Output::Fail('领取失败');
        } else {
            $user_id = Auth::id();

            $str = date('Y-m-d', time()) . ' 03:00:00';
            $h = date('h', time());
            $t = Carbon::createFromFormat('Y-m-d H:i:s', $str);
            if ($h >= 3) {
                $t = Carbon::createFromFormat('Y-m-d H:i:s', $str)->addDays(1);
            }
            $t2 = Carbon::createFromFormat('Y-m-d H:i:s', $str)->subDay(3);


            $list = Qiang::where('user_id', $user_id)->where('reward', '>', 0)->where('get', 0)->orderBy('id', 'desc')
                ->whereBetween('created_at', [$t, $t2])
                ->get();

            if ($request->ajax()) {
                return Output::Success('', $list);
            } else {
                return view('home.lq')->with('list', $list)->with('user', Auth::user());
            }
        }

    }


}
