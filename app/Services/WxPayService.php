<?php
/**
 * Created by PhpStorm.
 * User: ygy
 * Date: 2017/6/1
 * Time: 14:43
 */

namespace App\Services;


use App\Tools\Pay\Wxpay\WxpayTool;

class WxPayService
{
    public static function payParameter($fee,$order_no,$openid,$body){
        return WxpayTool::JsPay($body,$fee,$order_no,asset('/payCallback'),$openid);
    }

    public static function yjParameter($order,$openid){
        $fee = $order->amount;
        if(config('app.testenv')){
            $fee = 1;
        }
        return self::payParameter($fee,$order->order_no,$openid,'缴纳押金');
    }

    public static function orderParameter($order,$openid){
        $fee = $order->pay_amount;
        if(config('app.testenv')){
            $fee = 1;
        }
        return self::payParameter($fee,$order->order_no,$openid,'订单支付');
    }

}