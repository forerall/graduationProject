<?php

namespace App\Tool\Wechat\Pay\example;
require_once "../lib/WxPay.Api.php";
require_once "WxPay.NativePay.php";
require_once 'log.php';

class WxNativePay{
	public static function nativePay1(){
		$notify = new NativePay();
		$url1 = $notify->GetPrePayUrl("123456789");
		return $url1;
	}
	public static function nativePay2(){
		$notify = new NativePay();
		$input = new WxPayUnifiedOrder();
		$input->SetBody("test");
		$input->SetAttach("test");
		$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
		$input->SetTotal_fee("1");
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag("test");
		$input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
		$input->SetTrade_type("NATIVE");
		$input->SetProduct_id("123456789");
		$result = $notify->GetPayUrl($input);
		$url2 = $result["code_url"];
		return $url2;
	}
}
