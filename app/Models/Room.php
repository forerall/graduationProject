<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Room extends Model
{
    protected $table = 'room';
    protected $guarded = [];
    protected $appends = [
        'min_str',
        'max_str',
        'state_str',
    ];
    public static $stateArray = [
        1 => '正常',
        2 => '关闭',
    ];

    public function getStateStrAttribute()
    {
        if (isset(self::$stateArray[$this->state])) {
            return self::$stateArray[$this->state];
        }
        return '';
    }

    public function setMinAttribute($value)
    {
        $this->attributes['min'] = intval($value * 100);
    }

    public function setMaxAttribute($value)
    {
        $this->attributes['max'] = intval($value * 100);
    }

    public function getMinStrAttribute()
    {
        return number_format($this->min / 100, 2, '.', '');
    }

    public function getMaxStrAttribute()
    {
        return number_format($this->max / 100, 2, '.', '');
    }
}
