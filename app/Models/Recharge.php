<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Recharge extends Model
{
    protected $table = 'g_recharge';
    protected $guarded = [];
    protected $appends = ['money_str', 'state_str', 'pay_way_str'];

    public static $stateStrArray = [
        1 => '充值中',
        2 => '充值成功',
        3 => '充值失败',
    ];
    public static $PayWayStrArray = [
        'alipay' => '支付宝',
        'wxpay' => '微信',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStateStrAttribute()
    {
        if (isset(self::$stateStrArray[$this->state])) {
            return self::$stateStrArray[$this->state];
        }
        return '';
    }
    public function getPayWayStrAttribute()
    {
        if (isset(self::$PayWayStrArray[$this->pay_way])) {
            return self::$PayWayStrArray[$this->pay_way];
        }
        return '';
    }

    public function getMoneyStrAttribute()
    {
        return number_format($this->money / 100, 2, '.', '');
    }
}
