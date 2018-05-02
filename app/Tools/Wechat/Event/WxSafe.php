<?php

namespace App\Tools\Wechat\Event;


use App\Tools\Wechat\WxConfig;

class WxSafe{
    //消息推送验证
    public static function checkSignature($params = array(),$signature){
        $token = WxConfig::instance()->messageToken;
        if(empty($token)){
            throw new \Exception('messageToken 不能为空');
        }
        if(is_array($params)&&!empty($params)&&$signature&&$token) {
            $params[] = $token;
            sort($params, SORT_STRING);
            $tmpStr = implode( $params );
            $tmpStr = sha1( $tmpStr );
            if($tmpStr == $signature){
                return true;
            }
        }
        return false;
    }
}