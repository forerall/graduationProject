<?php
namespace App\Tools\Wechat;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class WxConfig
{
    public $appId = '';
    public $appSecret = '';
    public $messageToken = '';
    public $encodingAESKey = '';

    protected static $key = 'WxConfig';
    protected static $instance = null;

    //清除缓存
    public static function forget(){
        Cache::forget(self::$key);
    }

    public static function rawData(){
        return  Cache::rememberForever(self::$key, function() {
            $config = \App\Models\WxConfig::first();
            if(is_null($config)){
                $config = new \App\Models\WxConfig();
            }
            return $config;
        });
    }
    /**
     * @return null|static
     * @throws \Exception
     */
    public static function instance(){
        if(self::$instance){
            return self::$instance;
        }
        $data = self::rawData();
        if($data){
            self::$instance = new static();
            self::$instance->appId = $data->app_id;
            self::$instance->appSecret = $data->app_secret;
            self::$instance->messageToken = $data->message_token;
            self::$instance->encodingAESKey = $data->encoding_aes_key;
            return self::$instance;
        }
        throw new \Exception('微信配置不存在');
    }


}