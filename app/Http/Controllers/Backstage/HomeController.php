<?php

namespace App\Http\Controllers\Backstage;

use App\Http\PageConfig\RoomConfig;
use App\Models\Packet;
use App\Models\Qiang;
use App\Models\Recharge;
use App\Models\Setting;
use App\Models\UserBalanceLog;
use App\Models\Withdraw;
use App\Services\BalanceService;
use App\Tool\WeiboConnect;
use App\Service\SmsService;
use App\Tool\QQConnect;
use App\Tools\Generate\CreateCurd;
use App\Tools\Output;
use App\Tools\Wechat\WxConfig;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{

    public function dashboard(Request $request)
    {
        //echo __DIR__.'\\CategoryController.php';exit;
//        $file = file_get_contents(__DIR__.'\\CategoryController.php');
//        echo ($file);
//        exit;

//        $curl = new CreateCurd(new RoomConfig());
//        $curl->controller();
//        $curl->listPage();
//        $curl->updatePage();

        $url = '';
        if ($request->get('clear')) {
            WxConfig::forget();
        }

        return view('backstage.home.dashboard')->with('url', $url);
    }

    public function payWx(Request $request)
    {
        if ($request->isMethod('POST')) {
            $config = Setting::firstOrNew(['key' => 'payWx']);
            $config->value = $request->get('payWx');
            $result = $config->save();
            return Output::Result($result, '保存成功', '保存失败');
        } else {
            $config = Setting::firstOrNew(['key' => 'payWx']);
            return view('backstage.home.payWx')->with('config', $config);
        }

    }

    public function payAli(Request $request)
    {
        if ($request->isMethod('POST')) {
            $config = Setting::firstOrNew(['key' => 'payAli']);
            $config->value = $request->get('payAli');
            $result = $config->save();
            return Output::Result($result, '保存成功', '保存失败');
        } else {
            $config = Setting::firstOrNew(['key' => 'payAli']);
            return view('backstage.home.payAli')->with('config', $config);
        }
    }

    public function kf(Request $request)
    {
        if ($request->isMethod('POST')) {
            $config = Setting::firstOrNew(['key' => 'kf']);
            $config->value = $request->get('kf');
            $result = $config->save();
            return Output::Result($result, '保存成功', '保存失败');
        } else {
            $config = Setting::firstOrNew(['key' => 'kf']);
            return view('backstage.home.kf')->with('config', $config);
        }
    }

    public function cs(Request $request)
    {
        if ($request->isMethod('POST')) {
            $config = Setting::firstOrNew(['key' => 'choushui']);
            $config->value = $request->get('choushui');
            $result = $config->save();
            $config = Setting::firstOrNew(['key' => 'choushui1']);
            $config->value = $request->get('choushui1');
            $result = $config->save();
            $config = Setting::firstOrNew(['key' => 'choushui2']);
            $config->value = $request->get('choushui2');
            $result = $config->save();
            $config = Setting::firstOrNew(['key' => 'choushui3']);
            $config->value = $request->get('choushui3');
            $result = $config->save();
            $config = Setting::firstOrNew(['key' => 'gg']);
            $config->value = $request->get('gg');
            $result = $config->save();
            return Output::Result($result, '保存成功', '保存失败');
        } else {
            $config = Setting::firstOrNew(['key' => 'choushui']);
            $config1 = Setting::firstOrNew(['key' => 'choushui1']);
            $config2 = Setting::firstOrNew(['key' => 'choushui2']);
            $config3 = Setting::firstOrNew(['key' => 'choushui3']);
            $gg = Setting::firstOrNew(['key' => 'gg']);
            return view('backstage.home.cs')
                ->with('config', $config)
                ->with('config1', $config1)
                ->with('config2', $config2)
                ->with('gg', $gg)
                ->with('config3', $config3);
        }
    }

    public function zb(Request $request)
    {
        if ($request->isMethod('POST')) {
            $config = Setting::firstOrNew(['key' => 'zb']);
            $config->value = $request->get('zb');
            $result = $config->save();
            return Output::Result($result, '保存成功', '保存失败');
        } else {
            $config = Setting::firstOrNew(['key' => 'zb']);
            return view('backstage.home.zb')->with('config', $config);
        }
    }

    public function fa(Request $request)
    {
        if ($request->isMethod('POST')) {
            $this->validate($request, [
                'money' => 'required',
                'room_id' => 'required',
                'number' => 'required',
            ], [
                'room_id.required' => '请选择房间',
                'money.required' => '金额不能为空',
                'number.required' => '包数不能为空',
            ]);
            $money = intval($request->get('money') * 100);
            if ($money <= 0) {
                return Output::Fail('金额错误');
            }
            $number = intval($request->get('number'));
            if ($number <= 0) {
                return Output::Fail('包数错误');
            }
            $p = new Packet();
            $p->user_id = 0;
            $p->room_id = $request->get('room_id');
            $p->packet_money = $money;
            $p->money = $money;//抽成
            $p->lei = -1;
            $p->packets = $number;
            $p->peilv = 0;
            $p->got = 0;//已抢数量
            $p->got_money = 0;//已抢金额
            $p->msg = '福利包';
            $p->day = date('Ymd', time());
            $result = $p->save();
            return Output::Result($result, '发送成功', '发送失败');
        } else {
            return view('backstage.home.fa');
        }
    }

    public function chou(Request $request)
    {
        $day = $request->get('day');
        if ($day) {
            $list = Packet::with('room', 'user')->where('day', $day)->paginate(20);
            $sum = Packet::where('user_id', '>', 0)->where('day', $day)->sum('chou') * 0.01;
        } else {
            $list = Packet::with('room', 'user')->paginate(20);
            $sum = Packet::where('user_id', '>', 0)->sum('chou') * 0.01;
        }

        $sum = number_format($sum, 2, '.', '');
        $list->appends($request->except('page', 'per_page'));
        return view('backstage.home.chou')->with('list', $list)->with('sum', $sum);
    }

    public function listfa(Request $request)
    {
        $user_id = $request->get('uid');
        $list = UserBalanceLog::with('user')->where('user_id', $user_id)->paginate(20);
        $list->appends($request->except('page', 'per_page'));

        $recharge = UserBalanceLog::where('user_id', $user_id)
                ->where('type', 1)
                ->where('state', 2)
                ->sum('money') / 100;
        $consume = UserBalanceLog::where('user_id', $user_id)
                ->where('type', 3)
                ->where('state', 2)
                ->sum('money') / 100;
        $user = User::find($user_id);
        $balance = $user ? $user->balance_str : 0;

        return view('backstage.home.listfa')
            ->with('list', $list);
    }

    public function balance(Request $request)
    {
        $user_id = $request->get('uid');
        $list = UserBalanceLog::with('user')->where('user_id', $user_id)->paginate(20);
        $list->appends($request->except('page', 'per_page'));

        $recharge = UserBalanceLog::where('user_id', $user_id)
                ->where('type', 1)
                ->where('state', 2)
                ->sum('money') / 100;
        $consume = UserBalanceLog::where('user_id', $user_id)
                ->where('type', 3)
                ->where('state', 2)
                ->sum('money') / 100;
        $user = User::find($user_id);
        $balance = $user ? $user->balance_str : 0;

        return view('backstage.home.balance')
            ->with('list', $list)
            ->with('recharge', $recharge)
            ->with('balance', $balance)
            ->with('consume', $consume);
    }

    public function bz(Request $request)
    {
        $list = Qiang::with('user');//->where('reward', 1)->orderBy('id', 'desc')->paginate(20);
        $day = $request->get('day');

        if ($day) {
            $str = substr($day, 0, 4) . '-' . substr($day, 4, 2) . '-' . substr($day, 6, 2) . ' 03:00:00';
            $h = date('h', time());
            $t = Carbon::createFromFormat('Y-m-d H:i:s', $str);
            if ($h >= 3) {
                $t2 = Carbon::createFromFormat('Y-m-d H:i:s', $str)->addDays(1);
            } else {
                $t2 = Carbon::createFromFormat('Y-m-d H:i:s', $str)->subDay(1);
            }
            $list->whereBetween('created_at', [$t, $t2]);
        }
        $list = $list->where('reward', '>', 0)->orderBy('id', 'desc')->paginate(20);
        $list->appends($request->except('page', 'per_page'));

        if ($day) {
            $count1 = Qiang::where('reward', 1)
                ->where('money', '<=', 999)
                ->whereBetween('created_at', [$t, $t2])
                ->count();
            $count2 = Qiang::where('reward', 1)
                ->where('money', '<=', 9999)
                ->whereBetween('created_at', [$t, $t2])
                ->count();
            $count3 = Qiang::where('reward', 1)
                ->where('money', '<=', 99999)
                ->whereBetween('created_at', [$t, $t2])
                ->count();
        } else {
            $count1 = Qiang::where('reward', 1)
                ->where('money', '<=', 999)
                ->count();
            $count2 = Qiang::where('reward', 1)
                ->where('money', '<=', 9999)
                ->count();
            $count3 = Qiang::where('reward', 1)
                ->where('money', '<=', 99999)
                ->count();
        }
        $sum = $count1 * 1888 + ($count2 - $count1) * 8888 + ($count3 - $count2) * 28888;
        return view('backstage.home.bz')->with('list', $list)->with('sum', $sum / 100);
    }

    public function clearTips()
    {
        Recharge::where('read', 0)->update(['read' => 1]);
        Withdraw::where('read', 0)->update(['read' => 1]);
        return Output::Success('');
    }

    public function tips()
    {
        $count = Recharge::where('state', 1)->where('read', 0)->count();
        $count1 = Withdraw::where('state', 1)->where('read', 0)->count();
        return Output::Success('', $count + $count1 > 0 ? 1 : 0, $count > 0 ? 1 : 0);
    }

    public function fabz(Request $request)
    {
        $qid = $request->get('id');
        $qiang = Qiang::findOrFail($qid);
        if ($qiang->reward == 0 || $qiang->get != 0) {
            return Output::Fail('没有可以发放的奖励，请刷新页面重试！');
        }
        try {
            DB::beginTransaction();
            $update = Qiang::where('reward', '>', 0)
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
            $rewordMoney = Qiang::$rewordMoney;
            $money = 0;
            if(isset($rewordMoney[$qiang->reword])){
                $money = $rewordMoney[$qiang->reword];
            }
            $user_id = $qiang->user_id;
            $balanceService = new BalanceService();
            $balanceService->Back($user_id, $money, '奖励');
            DB::commit();
            return Output::Success('发放成功');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
        }
        return Output::Fail('发放失败');
    }
}
