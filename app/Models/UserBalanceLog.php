<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserBalanceLog extends Model
{
    protected $table = 'g_user_balance_log';
    protected $guarded = [];
    protected $appends = ['type_str', 'state_str', 'money_str', 'balance_str', 'created_at_short'];

    public static $typeStrArray = [
        1 => '充值',
        2 => '提现',
        3 => '支付',
        4 => '退款',
        5 => '订单收入',
        6 => '打赏',
        7 => '红包',
        8 => '代理佣金',
    ];
    public static $stateStrArray = [
        1 => [
            1 => '充值中',
            2 => '充值成功',
            3 => '充值失败',
        ],
        2 => [
            1 => '申请中',
            2 => '已付款',
            3 => '审核失败',
        ],
        7 => [
            1 => '已发送',
            2 => '已领取',
            3 => '已退回',
        ]
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getMoneyStrAttribute()
    {
        return ($this->money > 0 ? '+' : '') . number_format($this->money / 100, 2, '.', '');
    }

    public function getBalanceStrAttribute()
    {
        return number_format($this->balance / 100, 2, '.', '');
    }

    public function getStateStrAttribute()
    {
        if (isset(self::$stateStrArray[$this->type]))
            if (isset(self::$stateStrArray[$this->type][$this->state])) {
                return self::$stateStrArray[$this->type][$this->state];
            }
        return '';
    }

    public function getTypeStrAttribute()
    {
        if (isset(self::$typeStrArray[$this->type])) {
            return self::$typeStrArray[$this->type];
        }
        return '';
    }

    public function getCreatedAtShortAttribute()
    {
        return substr($this->created_at, 0, 10);
    }

}
