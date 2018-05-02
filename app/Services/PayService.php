<?php
/**
 * Created by PhpStorm.
 * User: arts-mgcx
 * Date: 2017/3/27
 * Time: 15:17
 */

namespace App\Services;


use App\Tools\Pay\AlipayTool;

class PayService
{
    public static $Alipay = 1;
    public static $instances = [];

    /**
     * pc端支付
     * @param $subject
     * @param $body
     * @param $out_trade_no
     * @param $total_amount
     * @param $pay_type
     * @return mixed
     * @throws \Exception
     */
    public static function pcPay($subject,$body,$out_trade_no,$total_amount,$pay_type){
        return self::getInstance($pay_type)->pcPay($subject,$body,$out_trade_no,$total_amount);
    }

    /**
     * 手机端支付
     * @param $subject
     * @param $body
     * @param $out_trade_no
     * @param $total_amount
     * @param $pay_type
     * @return mixed
     * @throws \Exception
     */
    public static function wapPay($subject,$body,$out_trade_no,$total_amount,$pay_type){
        return self::getInstance($pay_type)->wapPay($subject,$body,$out_trade_no,$total_amount);
    }

    /**
     * app 支付
     * @param $subject
     * @param $body
     * @param $out_trade_no
     * @param $total_amount
     * @param $pay_type
     * @return mixed
     * @throws \Exception
     */
    public static function appPay($subject,$body,$out_trade_no,$total_amount,$pay_type){
        return self::getInstance($pay_type)->appPay($subject,$body,$out_trade_no,$total_amount);
    }

    /**
     * 支付回调
     * @param $input
     * @param $pay_type
     * @return mixed
     * @throws \Exception
     */
    public static function notify($input,$pay_type){
        return self::getInstance($pay_type)->notify($input,$pay_type);
    }

    /**
     * 获取支付接口实例
     * @param $pay_type
     * @return mixed
     * @throws \Exception
     */
    protected static function getInstance($pay_type){
        if(!isset(self::$instances[$pay_type])){
            if($pay_type== self::$Alipay){
                self::$instances[$pay_type] = new AlipayTool();
            }
        }
        if(!isset(self::$instances[$pay_type])){
            throw new \Exception('支付类型不存在：'.$pay_type);
        }
        return self::$instances[$pay_type];
    }
}