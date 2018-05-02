<?php

namespace App\Http\Controllers;


use App\Tools\Wechat\Event\Msg\SubscribeMsg;
use App\Tools\Wechat\Event\Msg\TextMsg;
use App\Tools\Wechat\Event\WxMsgCallback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WxController extends Controller
{

    public function wxMsgCallback(Request $request)
    {
        try {
            $wx = new WxMsgCallback();
            return $wx->onTextMessage(function (WxMsgCallback $wx, TextMsg $msg) {
                return $wx->replyText('：' . $msg->Content);
            })->onSubscribeMessage(function (WxMsgCallback $wx, SubscribeMsg $msg) {
                if ($msg->subscribe()) {
                    return $wx->replyText('欢迎');
                }
            })->start();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

}
