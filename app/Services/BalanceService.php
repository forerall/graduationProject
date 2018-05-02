<?php
/**
 * Created by PhpStorm.
 * User: arts-mgcx
 * Date: 2016/10/19
 * Time: 11:45
 */
namespace App\Services;


use App\Models\M\Money;
use App\Models\Recharge;
use App\Models\Withdraw;
use App\Models\UserBalanceLog;
use App\Tools\Output;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BalanceService
{
    public static $typeRecharge = 1;//充值
    public static $typeWithdraw = 2;//提现
    public static $typePay = 3;//支付
    public static $typeReturn = 4;//退款
    public static $typeOrder = 5;//订单到账
    public static $typeReward = 6;//拍拍打赏
    public static $typeMoney = 7;//红包
    public static $typeChou = 8;//佣金

    protected $minusBalance = false;//允许负数余额

    public function orderMoney($user_id, $money, $detail = '')
    {
        $money = abs(intval($money));
        return $this->changeBalance($user_id, $money, $detail, self::$typeOrder);
    }

    //代理佣金
    public function chou($user_id, $money, $detail = '')
    {
        $money = abs(intval($money));
        return $this->changeBalance($user_id, $money, $detail, self::$typeChou);
    }

    /**
     * 充值
     * @param $user_id
     * @param $money
     * @param string $detail
     * @return bool
     * @throws \Exception
     */
    public function recharge($user_id, $money, $detail = '')
    {
        $money = abs(intval($money));
        return $this->changeBalance($user_id, $money, $detail, self::$typeRecharge);
    }


    /**
     * 申请提现
     * @param $user_id
     * @param $money
     * @param string $detail
     * @return bool
     * @throws \Exception
     */
    public function withdraw($user_id, $money, $detail = '')
    {
        $money = abs(intval($money));
        //提现申请添加未确认的余额变更日志
        return $this->changeBalance($user_id, -$money, $detail, self::$typeWithdraw, 1);
    }

    /**
     * 余额使用
     * @param $user_id
     * @param $money
     * @param string $detail
     * @return bool
     * @throws \Exception
     */
    public function pay($user_id, $money, $detail = '')
    {
        $money = abs(intval($money));
        return $this->changeBalance($user_id, -$money, $detail, self::$typePay);
    }

    /**
     * 退款
     * @param $user_id
     * @param $money
     * @param string $detail
     * @return bool
     * @throws \Exception
     */
    public function Back($user_id, $money, $detail = '')
    {
        $money = abs(intval($money));
        return $this->changeBalance($user_id, $money, $detail, self::$typeReturn);
    }

    /**
     * 提现处理
     * @param $withdraw
     * @param $pass  是否通过
     * @param $msg  审核信息
     * @return bool
     */
    public function withdrawConfirm($withdraw, $pass, $msg)
    {
        try {
            DB::beginTransaction();
            //查询提现对应的余额记录
            $balance_log = UserBalanceLog::findOrFail($withdraw->user_balance_log_id);
            if ($balance_log->type != self::$typeWithdraw || $balance_log->state != 1) {
                throw new \Exception('提现记录状态错误');
            }
            //修改余额记录状态
            $result = UserBalanceLog::where('id', $balance_log->id)
                ->where('type', self::$typeWithdraw)
                ->where('state', 1)
                ->update([
                    'state' => $pass ? 2 : 3,
                    'balance' => $pass ? $balance_log->balance : $balance_log->balance + abs($balance_log->money),
                ]);
            if (!$result) {
                throw new \Exception('修改状态失败');
            }
            //不通过恢复余额
            if (!$pass) {
                if (!User::where('id', $balance_log->user_id)->increment('balance', abs($balance_log->money))) {
                    throw new \Exception('增加余额失败');
                }
            }
            $result = Withdraw::where('id', $withdraw->id)
                ->where('state', 1)
                ->update([
                    'state' => $pass ? 2 : 3,
                    'msg' => $msg
                ]);
            if (!$result) {
                throw new \Exception('修改审核状态失败');
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
        }
        return false;
    }

    /**
     * 充值处理
     * @param $recharge
     * @param $pass
     * @param $msg
     * @return bool
     */
    public function rechargeConfirm($recharge, $pass, $msg = '充值')
    {
        try {
            DB::beginTransaction();
            if ($pass) {
                $this->recharge($recharge->user_id, $recharge->money, $msg);
            }
            $result = Recharge::where('id', $recharge->id)
                ->where('state', 1)
                ->update([
                    'state' => $pass ? 2 : 3,
                    'msg' => $msg
                ]);
            if (!$result) {
                throw new \Exception('修改审核状态失败');
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
        }
        return false;
    }

    /**
     * 余额转移
     * @param $from_user_id
     * @param $to_user_id
     * @param $money
     */
    public function rewardBalance($from_user_id, $to_user_id, $money)
    {
        $detail = '拍拍打赏';
        $this->changeBalance($from_user_id, -abs($money), $detail, self::$typeReward);
        $this->changeBalance($to_user_id, abs($money), $detail, self::$typeReward);
    }

    //发红包
    public function sendMoney($user_id, $rec_id, $money, $detail = '发红包')
    {
        $money = abs(intval($money));
        try {
            if ($money < 0) {
                throw new \Exception('金额错误');
            }
            DB::beginTransaction();
            $log_id = $this->changeBalance($user_id, -$money, $detail, self::$typeMoney, 1);
            $item = new Money();
            $item->user_id = $user_id;
            $item->rec_id = $rec_id;
            $item->money = $money;
            $item->log_id = $log_id;
            $item->state = 0;
            $item->save();
            DB::commit();
            return Output::Success('发送成功', $item);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return Output::Fail($e->getMessage());
        }
    }

    //领取红包
    public function recMoney($user_id, $money_id)
    {
        try {
            DB::beginTransaction();
            $money = Money::where('rec_id', $user_id)->findOrFail($money_id);
            if (!Money::where('id', $money_id)->where('rec_id', $user_id)->where('state', 0)->update(['state' => 1])) {
                throw new \Exception('已领取');
            }
            UserBalanceLog::where('id', $money->log_id)->update(['state' => 2]);
            $this->changeBalance($money->rec_id, $money->money, '红包', self::$typeMoney);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return false;
        }
    }

    //退还红包
    public function returnMoney($money_id)
    {
        $money = Money::findOrFail($money_id);
        if ($money->state != 0) {
            return false;
        }
        try {
            DB::beginTransaction();
            if (!Money::where('id', $money_id)->where('state', 0)->update(['state' => 2])) {
                throw new \Exception('退还红包失败');
            }
            $log = UserBalanceLog::findOrFail($money->log_id);
            UserBalanceLog::where('id', $money->log_id)->update([
                'state' => 3,
                'balance' => $log->balance + $money->money
            ]);
            if (!User::where('id', $log->user_id)->increment('balance', abs($log->money))) {
                throw new \Exception('增加余额失败');
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
        }
        return false;
    }


    /**
     * 变更余额
     * @param $user_id
     * @param $money    int 单位分
     * @param $detail
     * @param $type
     * @param int $logState 余额日志状态1未确认，2已确认，3已取消
     * @return bool
     * @throws \Exception
     */
    protected function changeBalance($user_id, $money, $detail, $type, $logState = 2)
    {
        if ($money > 0) {
            if (!User::where('id', $user_id)->increment('balance', $money)) {
                throw new \Exception('增加余额失败');
            }
        } else if ($money < 0) {
            if ($this->minusBalance) {
                if (!User::where('id', $user_id)->decrement('balance', -$money)) {
                    throw new \Exception('扣款失败');
                }
            } else {
                //余额不足
                if (User::where('id', $user_id)->where('balance', '>=', -$money)->count() == 0) {
                    throw new \Exception('余额不足');
                }
                if (!User::where('id', $user_id)->where('balance', '>=', -$money)->decrement('balance', -$money)) {
                    throw new \Exception('扣款失败');
                }
            }

        } else {
            return false;
        }
        $u = User::where('id', $user_id)->select()->first();
        $log = UserBalanceLog::create([
            'user_id' => $user_id,
            'type' => $type,
            'money' => $money,
            'detail' => $detail,
            'state' => $logState,
            'balance' => $u->balance
        ]);
        return $log->id;
    }


    /**
     * 发起提现申请
     * @param $user_id
     * @param $money
     * @param $account
     * @param $account_type
     * @return array
     */
    public function requestWithdraw($user_id, $money, $account, $account_type,$sk)
    {
        try {
            DB::beginTransaction();
            $user_balance_log_id = $this->withdraw($user_id, $money, '申请提现');
            if ($user_balance_log_id <= 0) {
                throw new \Exception('提交失败');
            }
            $item = new Withdraw();
            $item->user_id = $user_id;
            $item->account = $account;
            $item->account_type = $account_type;
            $item->sk = $sk;
            //$item->name = $name;
            $item->money = $money;
            $item->user_balance_log_id = $user_balance_log_id;
            $item->state = 1;
            $item->day = date('Ymd', time());
            $item->save();
            DB::commit();
            return Output::Success('提交成功');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
        }
        return Output::Fail('提交失败');
    }


}