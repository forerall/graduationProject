<?php
/**
 * Created by PhpStorm.
 * User: ygy
 * Date: 2017/6/5
 * Time: 17:45
 */

namespace App\Services;


use App\Models\Packet;
use App\Models\Qiang;
use App\Models\Recharge;
use App\Models\Room;
use App\Models\Setting;
use App\Models\Withdraw;
use App\Tools\Output;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GameService
{
    protected $balanceService;

    public static function systemUser()
    {
        $u = new User();
        $u->nickname = '管理员';
        $u->avatar = '/images/admin.png';
        return $u;
    }

    public function __construct(BalanceService $balanceService)
    {
        $this->balanceService = $balanceService;
    }

    //一级代理列表
    public function getProxyUserList($request)
    {
        $list = User::where('proxy', 1)->orderBy('id', 'desc')->paginate(10);
        $list->appends($request->except('page', 'per_page'));
        return $list;
    }

    //$user_id 的下线
    public function getSubUserList($request, $user_id)
    {
        $list = User::where('recommend_user_id', $user_id)
            ->orderBy('id', 'desc')->paginate(1000);
        $list->appends($request->except('page', 'per_page'));
        return $list;
    }

    //获取上下分报表
    public function getConsumeRecord($user_id, $day = null)
    {
        //DB::listen(function ($query) {echo $query->sql . '<br>';});
        if (is_null($day)) {
            $day = intval(date('Ymd', time()));
        }
        $list = User::where('recommend_user_id', $user_id)->get();
        $uids = $list->pluck('id')->toArray();

        if (empty($uids)) {
            $ups = [];
            $downs = [];
        } else {

            $ups = Recharge::whereIn('user_id', $uids)
                ->where('state', 2)//充值成功
                ->where('day', $day)
                ->groupBy('user_id')
                ->select('user_id', DB::Raw('sum(money) as total_money'))
                ->get()
                ->keyBy('user_id')
                ->toArray();
            $downs = Withdraw::whereIn('user_id', $uids)
                ->where('state', 2)//已付款
                ->where('day', $day)
                ->groupBy('user_id')
                ->select('user_id', DB::Raw('sum(money) as total_money'))
                ->get()
                ->keyBy('user_id')
                ->toArray();
        }

        $str = substr($day, 0, 4) . '-' . substr($day, 4, 2) . '-' . substr($day, 6, 2) . ' 03:00:00';
        $h = date('h', time());
        $t = Carbon::createFromFormat('Y-m-d H:i:s', $str);
        if ($h >= 3) {
            $t2 = Carbon::createFromFormat('Y-m-d H:i:s', $str)->addDays(1);
        } else {
            $t2 = Carbon::createFromFormat('Y-m-d H:i:s', $str)->subDay(1);
        }

        foreach ($list as $user) {
            if (array_key_exists($user->id, $ups)) {
                $user->up = number_format($ups[$user->id]['total_money'] / 100, 2, '.', '');
            } else {
                $user->up = 0;
            }
            if (array_key_exists($user->id, $downs)) {
                $user->down = number_format($downs[$user->id]['total_money'] / 100, 2, '.', '');;
            } else {
                $user->down = 0;
            }

            $user->packet = Packet::where('user_id', $user->id)->whereBetween('created_at', [$t, $t2])->count();
            $user->packet_money = Packet::where('user_id', $user->id)->whereBetween('created_at', [$t, $t2])->sum('packet_money') / 100;
        }
        return $list;
    }

    protected function getConfig($key)
    {
        $config = Setting::firstOrNew(['key' => $key]);
        $cs = $config->value ? $config->value : 0;
        //$cs = intval($cs);
        $cs = min($cs, 99);
        $cs = max($cs, 0);
        return $cs;
    }

    //发
    public function putPacket($room_id, $user_id, $money, $lei, $packets,$msg = '')
    {
        $money = intval($money);
        $room = Room::findOrFail($room_id);
        if ($room->min > $money || $room->max < $money) {
            return Output::Fail('金额不符合要求');
        }
        $user = User::findOrFail($user_id);
        if ($user->balance < $money) {
            return Output::Fail('余额不足');
        }
        try {
//            $cs = $this->getConfig('choushui');
//            $cs1 = $this->getConfig('choushui1');
//            $cs2 = $this->getConfig('choushui2');
//            $cs3 = $this->getConfig('choushui3');
//            $all = $cs;
//            $ids = [];
//            if ($user->recommend_user_id) {
//                if (User::where('proxy', '>', 0)->find($user->recommend_user_id)) {//上级
//                    $ids[] = $user->recommend_user_id;
//                    if (User::where('proxy', '>', 0)->find($user->recommend_user_id2)) {//上上级
//                        $ids[] = $user->recommend_user_id2;
//                        if (User::where('proxy', '>', 0)->find($user->recommend_user_id1)) {//上上级
//                            $ids[] = $user->recommend_user_id1;
//                        }
//                    }
//                }
//            }
//            if (count($ids) == 1) {
//                $cc = [$cs1];
//            } else if (count($ids) == 2) {
//                $cc = [$cs2, $cs1];
//            } else if (count($ids) == 3) {
//                $cc = [$cs3, $cs2, $cs1];
//            } else {
//                $cc = [];
//            }
//            for ($i = 0; $i < count($ids); $i++) {
//                $all += $cc[$i];
//                $this->balanceService->chou($ids[$i], intval($money * $cc[$i] / 100), '代理佣金');
//            }
//            $all = min(99, $all);

            $pingtai = 3;
            $cs = 6;
            $ids = [];
            if ($user->recommend_user_id) {
                if (User::where('proxy', '>', 0)->find($user->recommend_user_id)) {//上级
                    $ids[] = $user->recommend_user_id;
                    if (User::where('proxy', '>', 0)->find($user->recommend_user_id2)) {//上上级
                        $ids[] = $user->recommend_user_id2;
                        if (User::where('proxy', '>', 0)->find($user->recommend_user_id1)) {//上上级
                            $ids[] = $user->recommend_user_id1;
                        }
                    }
                }
            }
            $count = count($ids);


            DB::beginTransaction();
            if ($user->proxy == 0) {//自己不是代理
                if ($count == 1) {
                    $this->balanceService->chou($ids[0], intval($money * 3 / 100), '代理佣金');
                } else if ($count == 2) {
                    $this->balanceService->chou($ids[1], intval($money * 0.6 / 100), '代理佣金');
                    $this->balanceService->chou($ids[0], intval($money * 2.4 / 100), '代理佣金');
                } else if ($count == 3) {
                    $this->balanceService->chou($ids[2], intval($money * 0.6 / 100), '代理佣金');
                    $this->balanceService->chou($ids[1], intval($money * 0.6 / 100), '代理佣金');
                    $this->balanceService->chou($ids[0], intval($money * 1.8 / 100), '代理佣金');
                } else {
                    $pingtai = 6;
                }
            } else {//自己是代理
                if ($count == 1) {
                    //自己是二级
                    $this->balanceService->chou($user->id, intval($money * 2.4 / 100), '代理佣金');
                    $this->balanceService->chou($ids[0], intval($money * 0.6 / 100), '代理佣金');
                } else if ($count == 2) {
                    //自己是三级
                    $this->balanceService->chou($user->id, intval($money * 1.8 / 100), '代理佣金');
                    $this->balanceService->chou($ids[0], intval($money * 0.6 / 100), '代理佣金');
                    $this->balanceService->chou($ids[1], intval($money * 0.6 / 100), '代理佣金');
                } else if ($count == 0) {
                    //自己是一级
                    $this->balanceService->chou($user->id, intval($money * 3 / 100), '代理佣金');
                }
            }
            $cs = 5;
            $p = new Packet();
            $p->user_id = $user_id;
            $p->room_id = $room_id;
            $p->packet_money = $money;
            $p->money = intval($money * (100 - $cs) / 100);//抽成
            $p->chou = 0;//intval($money * $pingtai / 100);//抽成
            $p->lei = intval($lei);
            $p->packets = $packets;
            $p->peilv = $room->peilv;
            $p->got = 0;//已抢数量
            $p->got_money = 0;//已抢金额
            $p->msg = $msg;
            $p->max_packet = mt_rand(1, $packets);
            $p->day = date('Ymd', time());
            $p->save();
            $this->balanceService->pay($user_id, $money, '发红包');
            $room->send = time();
            $room->save();
            DB::commit();
            return Output::Success('发送成功');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
        }
        return Output::Fail('发送失败');
    }

    //抢
    public function getPacket($user_id, $packet_id)
    {
        $pp = [
            5 => 2,
            6 => 1.7,
            7 => 1.5,
            8 => 1.3,
            9 => 1.2,
            10 => 1.1,
        ];

        $packet = Packet::findOrFail($packet_id);
        $peilv = $packet->packets;
        if ($packet->packets <= $packet->got) {
            return Output::Fail('手慢了，红包已抢完');
        }
        if ($packet->state == 1) {
            return Output::Fail('红包已过期');
        }
        if (Qiang::where('user_id', $user_id)->where('packet_id', $packet_id)->count() > 0) {
            return Output::Fail('已抢过红包');
        }
        $user = User::findOrFail($user_id);
        if ($packet->lei > -1 && $user->balance < $packet->money * $peilv) {
            return Output::Fail('余额不足赔付，无法抢红包');
        }
        try {
            DB::beginTransaction();
            $packet = Packet::where('id', $packet_id)->lockForUpdate()->first();
            if ($packet->got >= $packet->packets) {
                throw new \Exception('红包已抢空');
            }
            if (Qiang::where('user_id', $user_id)->where('packet_id', $packet_id)->count() > 0) {
                throw new \Exception('已抢过红包');
            }
            $remain_money = $packet->money - $packet->got_money - ($packet->packets - $packet->got)*mt_rand(1,5);//可抢金额

//            $setting = Setting::firstOrNew(['key' => 'zb']);
//            if ($setting->value > 0 && $user->rate == 0) {
//                $user->rate = max(1, 95 - $setting->value/10);
//            }
//            $user->rate += 5;//$packet->packets/2;
//            $r = mt_rand(1, 20);
//            if ($packet->max_packet == $packet->got) {
//                $r = mt_rand(20, 35);
//            }
//
//            $leiRate = mt_rand(1, 100);
//            if ($user->rate > 0 && $leiRate > $user->rate && $packet->lei > -1 && $packet->lei < 10) {
//
//                //有设置胜率
//                //输
//                $money = intval($remain_money / ($packet->packets - $packet->got + 1) * $r / 10);
//                //$money = mt_rand(1, $remain_money * mt_rand(1, 5) / 10);
//                $money = strval($money);
//                $len = strlen($money);
//                $money[$len - 1] = intval($packet->lei);
//                if ($money == 0) {
//                    $money = mt_rand(1, 9);
//                }
////                if($user->id==5){
////                    Log::info('随机数：'.$r.'金额：'.$money);
////                }
//                if ($money > $remain_money) {
//                    $money = $remain_money;
//                }
//
//
//            } else {
//                //$money = mt_rand(1, $remain_money * mt_rand(1, 5) / 10);
//                $money = intval($remain_money / ($packet->packets - $packet->got + 1) * $r / 10);
//                $money_str = substr($money, -1);
//                if (strrpos($money_str, strval($packet->lei)) !== false) {
//                    $money--;
//                    $money = max(2, $money);
//                }
//                $money = max(1, $money);
//                if ($money > $remain_money) {
//                    $money = $remain_money;
//                }
//            }

            $r = mt_rand(1, 20);
            $money = intval($remain_money / ($packet->packets - $packet->got) * $r / 10);
            $money = max(1, $money);
            if ($money > $remain_money) {
                $money = $remain_money;
            }
            $money = intval($money);
            $packet->got++;
            //最后一包
            if ($packet->got == $packet->packets) {
                $money = $remain_money;
            }
            $packet->got_money += $money;
            $packet->save();
            //后两位
            $money_str = substr($money, -1);
            $lei = strrpos($money_str, strval($packet->lei)) !== false;
            $baozi = 0;
            $xiaobao1 = [111, 222, 333, 444, 555, 666, 777, 888, 999];
            $xiaobao = [1111, 2222, 3333, 4444, 5555, 6666, 7777, 8888, 9999];
            $dabao = [11111, 22222, 33333, 44444, 55555, 66666, 77777, 88888, 99999];
            if (in_array($money, $xiaobao1)) {
                $baozi = 1;
            }
            if (in_array($money, $xiaobao)) {
                $baozi = 2;
            }
            if (in_array($money, $dabao)) {
                $baozi = 3;
            }
            if (in_array($money, [520, 1314])) {
                $baozi = 4;
            }
            $q = new Qiang();
            $q->user_id = $user_id;
            $q->packet_id = $packet_id;
            $q->money = $money;
            $q->get = 0;
            $q->reward = $baozi;
            $q->type = $lei ? 1 : 0;
            $q->save();
            if ($q->type == 1) {//雷赔钱
                $pei = abs($peilv * $packet->packet_money);
                $this->balanceService->pay($user_id, $pei, '抢到雷赔付');
                $this->balanceService->Back($packet->user_id, $pei, '雷赔付款');
            } else {
                $this->balanceService->Back($user_id, $money, '抢红包');
            }
            DB::commit();
            return Output::Success('', $q);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return Output::Fail($e->getMessage());
        }

    }

    //福利房
    public function getRooms2()
    {
        $list = Room::where('state', 1)->where('password', '<>', '')->get();
        foreach ($list as $room) {
            $room->people = User::where('room_id', $room->id)
                ->where(function ($query) {
                    $query->where('active_time', '>', time() - 3600)
                        ->orWhere('type', 1);
                })
                ->count();
            if ($room->id == 1 && $room->people < 50) {
                $room->people = mt_rand(50, 60);
            }
        }
        return $list;
    }

    //房间
    public function getRooms()
    {
        $list = Room::where('state', 1)->where('password', '')->get();
        foreach ($list as $room) {
            $room->people = User::where('room_id', $room->id)
                ->where(function ($query) {
                    $query->where('active_time', '>', time() - 3600)
                        ->orWhere('type', 1);
                })
                ->count();
            if ($room->id == 1 && $room->people < 50) {
                $room->people = mt_rand(50, 60);
            }
        }
        return $list;
    }

    public function returnMoney()
    {
        $list = Packet::where('state', 0)//whereColumn('packets', '>', 'got')
        ->where('created_at', '<', Carbon::now()->subMinute(10))
            ->get();
        foreach ($list as $packet) {

            try {
                DB::beginTransaction();
                if (Packet::where('id', $packet->id)->where('state', 0)->update(['state' => 1])) {
                    if ($packet->money - $packet->got_money > 0 && $packet->user_id > 0) {
                        $this->balanceService->Back($packet->user_id, $packet->money - $packet->got_money, '剩余红包退款');
                    }
                    //
                    $count = Qiang::where('packet_id', $packet->id)->where('reward', '>', 0)->count();
                    if (in_array($count, [5, 6, 7, 8])) {
                        $q = new Qiang();
                        $q->user_id = $packet->user_id;
                        $q->packet_id = $packet->id;
                        $q->money = -1;
                        $q->type = 0;
                        $q->get = 0;
                        $q->reward = $count;
                        $q->save();
                    }
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error($e);
            }
        }
    }

}