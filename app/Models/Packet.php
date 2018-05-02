<?php

namespace App\Models;

use App\Services\CommonService;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Packet extends Model
{
    protected $table = 'packet';
    protected $guarded = [];
    protected $appends = ['money_str', 'packet_money_str', 'got_money_str', 'can_get'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function getCanGetAttribute()
    {
        return $this->packets > $this->got && $this->state == 0;
    }

    public function getGotMoneyStrAttribute()
    {
        return CommonService::intToDecimalMoney($this->got_money);
    }

    public function getPacketMoneyStrAttribute()
    {
        return CommonService::intToDecimalMoney($this->packet_money);
    }

    public function getMoneyStrAttribute()
    {
        return CommonService::intToDecimalMoney($this->money);
    }
}
