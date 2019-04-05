<?php

namespace App;

use App\Models\M\Store;
use App\Models\Room;
use App\Services\CommonService;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $table = 'g_users';
    protected $guarded = [];
    protected $appends = [
        'balance_str',
        'avatar_url',
        'proxy_str',
        'recom_code',
    ];
    protected $hidden = [
        'password', 'remember_token', 'pay_password',
    ];

    public static $proxyArray = [
        0 => '普通用户',
        1 => '股东代理',
        2 => '普通代理',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    //passport用户查找方法
    public function findForPassport($username)
    {
        return self::where('phone', $username)->first();
    }

    public function getBalanceStrAttribute()
    {
        return CommonService::intToDecimalMoney($this->balance);
    }

    public function setDecimalBalance($decimalMoney)
    {
        $this->balance = CommonService::decimalToIntMoney($decimalMoney);
    }

    public function getRecomCodeAttribute()
    {
        return '1' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    public function getProxyStrAttribute()
    {
        if (array_key_exists($this->proxy, self::$proxyArray)) {
            return self::$proxyArray[$this->proxy];
        }
        return '';
    }

    public function getAvatarAttribute($avatar)
    {
        if (empty($avatar)) {
            return asset('/images/avatar.png');
        }
        return $avatar;
    }

    public function getAvatarUrlAttribute()
    {
        if (empty($this->avatar)) {
            return asset('/images/avatar.png');
        }
        $avatar = $this->avatar;
        if (strrpos('.', $this->avatar) > 0) {
            $avatar = substr($this->avatar, 0, strrpos('.', $this->avatar)) . '_s' . substr($this->avatar, strrpos('.', $this->avatar));
        }
        return asset($avatar);
    }


}
