<?php
namespace App\Tools\Pay\Wxpay\lib;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

require_once "WxPay.Api.php";

/**
 * Created by PhpStorm.
 * User: ygy
 * Date: 2017/5/18
 * Time: 9:56
 */
class WxPay
{
    public static function JsPay($body, $fee, $order_on, $notifyUrl, $openId)
    {
        require_once "WxPay.JsApiPay.php";
        //①、获取用户openid
        $tools = new \JsApiPay();
//        $openId = $tools->GetOpenid();

        //②、统一下单
        $input = new \WxPayUnifiedOrder();
        $input->SetBody($body);
        $input->SetAttach("test");
        $input->SetOut_trade_no($order_on);
        $input->SetTotal_fee($fee);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");
        $input->SetNotify_url($notifyUrl);
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $order = \WxPayApi::unifiedOrder($input);
        $jsApiParameters = $tools->GetJsApiParameters($order);
        return $jsApiParameters;
    }

    public static function appPay($body, $fee, $order_on, $notifyUrl)
    {
        require_once "WxPay.JsApiPay.php";
        $tools = new \JsApiPay();

        //②、统一下单
        $input = new \WxPayUnifiedOrder();
        $input->SetBody($body);
        $input->SetAttach("test");
        $input->SetOut_trade_no($order_on);
        $input->SetTotal_fee($fee);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");
        $input->SetNotify_url($notifyUrl);
        $input->SetTrade_type("APP");
        $order = \WxPayApi::unifiedOrder($input);
        $jsApiParameters = $tools->GetAppParameters($order);
        return $jsApiParameters;
    }

    public static function callback($callback)
    {
        try{
            require_once 'WxPay.Notify.php';
            $msg = '';
            $data = \WxpayApi::notify(null, $msg);
            $ret = new \WxPayNotify();
            if ($data === false) {
                //验证签名失败
                $ret->SetReturn_code("FAIL");
                $ret->SetReturn_msg($msg);
                return $ret->ToXml();
            }
            if(call_user_func($callback, $data)){
                $ret->SetReturn_code("SUCCESS");
                $ret->SetReturn_msg("OK");
                return $ret->ToXml();
            }else{
                $ret->SetReturn_code("FAIL");
                return $ret->ToXml();
            }

        }catch(\Exception $e){
            Log::error($e);
            $ret->SetReturn_code("FAIL");
            return $ret->ToXml();
        }

    }
}