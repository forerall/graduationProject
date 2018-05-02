<?php
/**
 * Created by PhpStorm.
 * User: arts-mgcx
 * Date: 2016/10/19
 * Time: 11:45
 */
namespace App\Tools\Pay\Alipay;

use App\Tools\Pay\Alipay\request\AlipayTradeAppPayRequest;
use App\Tools\Pay\Alipay\request\AlipayTradeFastpayRefundQueryRequest;
use App\Tools\Pay\Alipay\request\AlipayTradePagePayRequest;
use App\Tools\Pay\Alipay\request\AlipayTradeQueryRequest;
use App\Tools\Pay\Alipay\request\AlipayTradeRefundRequest;
use App\Tools\Pay\Alipay\request\AlipayTradeWapPayRequest;

class AlipayTool
{
    //pc支付
    public function pcPay($subject,$body,$order_no,$total_amount){
        $param = [];
        $param['subject'] = $subject;
        $param['body'] = $body;
        $param['out_trade_no'] = $order_no;
        $param['total_amount'] = $total_amount;
        $param['product_code'] = 'QUICK_WAP_PAY';
        //$param['timeout_express'] = "10m";

        $aop = new AopClient();
        $request = new AlipayTradePagePayRequest();
        $request->setBizContent(json_encode($param));
        $result = $aop->pageExecute($request);
        return $result;
    }
    //手机支付
    public function wapPay($subject,$body,$order_no,$total_amount){
        $param = [];
        $param['subject'] = $subject;
        $param['body'] = $body;
        $param['out_trade_no'] = $order_no;
        $param['total_amount'] = $total_amount;
        $param['product_code'] = 'QUICK_WAP_PAY';
        //$param['timeout_express'] = "10m";

        $aop = new AopClient();
        $request = new AlipayTradeWapPayRequest();
        $request->setBizContent(json_encode($param));
        $result = $aop->pageExecute($request);
        return $result;
    }
    //app支付
    public function appPay($subject,$body,$order_no,$total_amount){
        $param = [];
        $param['subject'] = $subject;
        $param['body'] = $body;
        $param['out_trade_no'] = $order_no;
        $param['total_amount'] = $total_amount;
        $param['product_code'] = 'QUICK_MSECURITY_PAY';

        $aop = new AopClient();
        $request = new AlipayTradeAppPayRequest();
        $request->setNotifyUrl(asset('/alipayCallback'));
        //$request->setNotifyUrl('http://www.yuxiusi.com/alipayCallback');
        $request->setBizContent(json_encode($param));
        //$result = $aop->execute($request);
        $result = $aop->sdkExecute($request);
        return $result;
    }
    //订单退款
    public function refund($order_no,$trade_no,$refund_amount){
        $aop = new AopClient ();
        $request = new AlipayTradeRefundRequest();
        $data = [
            'out_trade_no' => $order_no,
            'trade_no' => $trade_no,
            'refund_amount'=>$refund_amount,
        ];
        $request->setBizContent(json_encode($data));
        $result = $aop->execute($request);
        //dd($result);
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;
        if(!empty($resultCode)&&$resultCode == 10000){
            echo "成功";
        } else {
            echo "失败";
        }
    }
    //订单退款查询
    public function refundQuery($order_no,$trade_no,$out_request_no){
        $aop = new AopClient ();
        $request = new AlipayTradeFastpayRefundQueryRequest();
        $data = [
            'out_trade_no' => $order_no,
            'trade_no' => $trade_no,
            'out_request_no' => $out_request_no,
        ];
        $request->setBizContent(json_encode($data));
        $result = $aop->execute($request);
        //dd($result);
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;
        if(!empty($resultCode)&&$resultCode == 10000){
            echo "成功";
        } else {
            echo "失败";
        }
    }
    //订单查询
    public function query($order_no,$trade_no){
        $aop = new AopClient ();
        $request = new AlipayTradeQueryRequest();
        $data = [
            'out_trade_no' => $order_no,
            'trade_no' => $trade_no
        ];
        $request->setBizContent(json_encode($data));
        $result = $aop->execute($request);
//        {#259 ▼
//            +"alipay_trade_query_response": {#258 ▼
//            +"code": "10000"
//            +"msg": "Success"
//            +"buyer_logon_id": "183****3646"
//            +"buyer_pay_amount": "0.00"
//            +"buyer_user_id": "2088602345366283"
//            +"invoice_amount": "0.00"
//            +"open_id": "20880078196687587437668792812028"
//            +"out_trade_no": "1020170221234251616180521"
//            +"point_amount": "0.00"
//            +"receipt_amount": "0.00"
//            +"send_pay_date": "2017-02-21 23:42:58"
//            +"total_amount": "0.01"
//            +"trade_no": "2017022121001004280209231425"
//            +"trade_status": "TRADE_SUCCESS"
//          }
//          +"sign": "lNndjWYRiUTAE5KpnJvZIHHpgNRYeVXMmlt1vLM/KkS9+6zFMxQx33VclXVYaY1lT3snTLtoF29aw+lCfAlU/k4Wb+yG4AuGKwZ376gOHgsPsRqucJ7NmOErkEVpP+0dE3vWXUF6HV1LQzw0BuaXA8I5G04dKbWHpV7j3MO6FsU="
//        }
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;
        if(!empty($resultCode)&&$resultCode == 10000){
            echo "成功";
        } else {
            echo "失败";
        }
    }
    //支付回调
    public function notify($input){
        $sign_type = $input['sign_type'];
        $aop = new AopClient ();
        if($aop->rsaCheckV1($input,'',$sign_type)!==true){
            throw new \Exception('签名错误');
        }
//        array:25 [▼
//        "total_amount" => "0.01"
//        "buyer_id" => "2088602345366283"
//        "trade_no" => "2017022121001004280209231425"
//        "notify_time" => "2017-02-21 23:42:58"
//        "subject" => "【2元游】订单"
//        "sign_type" => "RSA"
//        "buyer_logon_id" => "183****3646"
//        "auth_app_id" => "2016012201113045"
//        "charset" => "UTF-8"
//        "notify_type" => "trade_status_sync"
//        "invoice_amount" => "0.01"
//        "out_trade_no" => "1020170221234251616180521"
//        "trade_status" => "TRADE_SUCCESS"
//        "gmt_payment" => "2017-02-21 23:42:58"
//        "version" => "1.0"
//        "point_amount" => "0.00"
//        "sign" => "hJf9uz+UxpwVcvP1tRKPJQ3DJJIvCcV/84+SmtUQxao6gLV2DFH0zcj8kzskj4iotQa3bNsy3ImWNho2cBvaMxT4SAHuKqpEjlimyajJiR0qzlVsf+Cf310cmJZElrGcDNOr9aPYWQxUsfhldWNqAgsr7dfL0014Nf2N+27nb6o="
//        "gmt_create" => "2017-02-21 23:42:57"
//        "buyer_pay_amount" => "0.01"
//        "receipt_amount" => "0.01"
//        "fund_bill_list" => "[{"amount":"0.01","fundChannel":"ALIPAYACCOUNT"}]"
//        "app_id" => "2016012201113045"
//        "seller_id" => "2088121799623202"
//        "notify_id" => "5b1fdb4e3018f0898971d3ddbfc784ei5u"
//        "seller_email" => "2138043415@qq.com"
//        ]
        $trade_no = $input['trade_no'];
        $order_no = $input['out_trade_no'];
        return true;
    }
}