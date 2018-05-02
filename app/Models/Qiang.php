<?php

namespace App\Models;

use App\Services\CommonService;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Qiang extends Model
{
    protected $table = 'qiang';
    protected $guarded = [];
    protected $appends = ['money_str', 'display_money', 'reward_money'];
    public static $rewordMoney = [
        1 => 1888,
        2 => 8880,
        3 => 18880,
        4 => 1288,
        5 => 3888,
        6 => 5888,
        7 => 12888,
        8 => 18888,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function getMoneyStrAttribute()
    {
        return CommonService::intToDecimalMoney($this->money);
    }
    public function getDisplayMoneyAttribute()
    {
        if($this->money>0){
            return CommonService::intToDecimalMoney($this->money);
        }else{
            return $this->reward.'雷奖励';
        }
    }
    public function getRewardMoneyAttribute()
    {
        if(array_key_exists($this->reward,self::$rewordMoney)){
            return self::$rewordMoney[$this->reward]/100;
        }
    }
}
