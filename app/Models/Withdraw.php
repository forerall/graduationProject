<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    protected $table = 'g_withdraw';
    protected $guarded = [];
    protected $appends = ['money_str', 'state_str','account_type_str'];

    public static $stateStrArray = [
        1 => '申请中',
        2 => '已付款',
        3 => '审核失败',
    ];
    public static $accountTypeStrArray = [
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
    public function getAccountTypeStrAttribute()
    {
        if (isset(self::$accountTypeStrArray[$this->account_type])) {
            return self::$accountTypeStrArray[$this->account_type];
        }
        return '';
    }

    public function getMoneyStrAttribute()
    {
        return number_format($this->money / 100, 2, '.', '');
    }
}
