<?php
namespace App\Tools\Wechat\Event\Msg;

//关注消息
class SubscribeMsg extends WxMsg
{
    public $Event;

    public function subscribe(){
        return $this->Event == 'subscribe';
    }
}