<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    public static $cacheKey = 'g_setting';
    protected $table = 'g_setting';
    protected $guarded = [];

    public static function getValue($key = false,$default = false){
        $data = Cache::rememberForever(self::$cacheKey, function() {
            return self::all()->keyBy('key');
        });
        if(false === $key){
            return $data;
        }
        if(isset($data[$key])){
            return $data[$key]->val;
        }
        return $default;
    }

    public static function forgetSetting(){
        Cache::forget(self::$cacheKey);
    }
}
