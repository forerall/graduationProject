<?php
/**
 * Created by PhpStorm.
 * User: arts-mgcx
 * Date: 2017/3/27
 * Time: 15:17
 */

namespace App\Tools\Pay;

use App\Tools\Pay\Alipay\AlipayTool;
use App\Tools\Pay\Wxpay\lib\WxPay;

/**
 * 支付宝和微信支付函数
 * Class PayService
 * @package App\Tools\Pay
 */
class PayService
{
    protected static $instances = [];
    public static $Alipay = 'alipay';
    public static $Wxpay = 'wxpay';
    public static $Testpay = 'testPay';

    //支付宝支付
    public static function pcAliPay($subject, $body, $order_no, $total_amount)
    {
        return self::getAlipayInstance()->pcPay($subject, $body, $order_no, $total_amount);
    }

    public static function wapAliPay($subject, $body, $order_no, $total_amount)
    {
        return self::getAlipayInstance()->wapPay($subject, $body, $order_no, $total_amount);
    }

    public static function appAliPay($subject, $body, $order_no, $total_amount)
    {
        return self::getAlipayInstance()->appPay($subject, $body, $order_no, $total_amount);
    }

    public static function alipayNotify($input)
    {
        return self::getAlipayInstance()->notify($input);
    }

    //微信支付
    public static function appWxPay($body, $total_amount, $order_on, $notifyUrl)
    {
        return self::getWxpayInstance()->appPay($body, $total_amount, $order_on, $notifyUrl);
    }

    public static function jsWxPay($body, $total_amount, $order_on, $notifyUrl, $openId)
    {
        return self::getWxpayInstance()->JsPay($body, $total_amount, $order_on, $notifyUrl, $openId);
    }

    public static function wxpayNotify($callback)
    {
        return self::getWxpayInstance()->callback($callback);
    }


    //app支付
    public static function getAppPayStr($pay_type, $pay_amount, $order_no, $subject = '订单付款')
    {
        if (config('app.testenv')) {
            $pay_amount = 1;//测试环境支付1分
        }
        $notifyUrl = asset('/wxpayCallback');
        if ($pay_amount < 1) {
            throw new \Exception('支付金额错误:' . $pay_amount . '(分)');
        }
        switch ($pay_type) {
            case PayService::$Alipay:
                $r = PayService::appAliPay($subject, '', $order_no, $pay_amount / 100);
                break;
            case PayService::$Wxpay:
                $r = PayService::appWxPay($subject, $pay_amount, $order_no, $notifyUrl);
                break;
            case PayService::$Testpay:
                $r = '';
                break;
        }
        return $r;
    }

    //支付实例获取
    /**
     * @return \App\Tools\Pay\Alipay\AlipayTool
     * @throws \Exception
     */
    protected static function getAlipayInstance()
    {
        return self::getInstance(self::$Alipay);
    }

    /**
     * @return \App\Tools\Pay\Wxpay\lib\WxPay
     * @throws \Exception
     */
    protected static function getWxpayInstance()
    {
        return self::getInstance(self::$Wxpay);
    }

    protected static function getInstance($type)
    {
        if (!array_key_exists($type, self::$instances)) {
            switch ($type) {
                case self::$Alipay:
                    self::$instances[$type] = new AlipayTool();
                    break;
                case self::$Wxpay:
                    self::$instances[$type] = new WxPay();
                    break;
            }
        }
        if (!isset(self::$instances[$type])) {
            throw new \Exception('支付类型不存在：' . $type);
        }
        return self::$instances[$type];
    }
}