<?php
/**
 * Created by PhpStorm.
 * User: arts-mgcx
 * Date: 2016/10/19
 * Time: 11:45
 */
namespace App\Services;


use App\Services\Ser\OrderService;
use App\Tools\Pay\PayService;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;


class PayCallbackService
{
    protected $balanceService;
    protected $orderService;
    protected $payService;

    public function __construct(BalanceService $balanceService, OrderService $orderService, PayService $payService)
    {
        $this->balanceService = $balanceService;
        $this->orderService = $orderService;
        $this->payService = $payService;
    }

    public function alipayCallback()
    {
        $input = Input::all();
        //$str ='{"total_amount":"1.00","buyer_id":"2088002057761561","trade_no":"2017042421001004560230021711","notify_time":"2017-04-24 15:02:26","subject":"\u30102\u5143\u6e38\u3011\u8ba2\u5355","sign_type":"RSA","buyer_logon_id":"huz***@sina.com","auth_app_id":"2016012201113045","charset":"UTF-8","notify_type":"trade_status_sync","invoice_amount":"1.00","out_trade_no":"1020170424150220262560141","trade_status":"TRADE_SUCCESS","gmt_payment":"2017-04-24 15:02:25","version":"1.0","point_amount":"0.00","sign":"lGjFBnzKCFytjAY\/++SpvmeHjc7VFVbzmXdm39hrxoaE15+pOc+dgtQViYwXaBOeyKiwpdZMsL8w2V5odBCCutY\/GpL4KnGJ07MPCgyFcjHTr8irB+TX58Mujd9MKo3+6JLj1BLoAS9DH2wiztzE+LmmYm6eWZ1p\/ljB0P4g+Ao=","gmt_create":"2017-04-24 15:02:24","buyer_pay_amount":"1.00","receipt_amount":"1.00","fund_bill_list":"[{\"amount\":\"1.00\",\"fundChannel\":\"ALIPAYACCOUNT\"}]","app_id":"2016012201113045","seller_id":"2088121799623202","notify_id":"642fd767fe14b19522f2b4ba93b4c75kbm","seller_email":"2138043415@qq.com"}';
        //$input = json_decode($str,true);
        try {
            //dd($input);
            $this->payService->alipayNotify($input);
            $order_no = $input['out_trade_no'];
            $order_amount = $input['total_amount'];
            $transaction_id = $input['trade_no'];

            if ($this->payCallback(PayService::$Alipay, $order_amount, $transaction_id, $order_no)) {
                return 'success';
            }

        } catch (\Exception $e) {
            Log::error($e);
        }
        return 'fail';
    }

    public function wxpayCallback()
    {
        return $this->payService->wxpayNotify([$this, '_wxpayCallback']);
    }

    public function _wxpayCallback($data)
    {
        /*
  'appid' => 'wx3445fa3bcdceff74',
  'attach' => 'test',
  'bank_type' => 'CFT',
  'cash_fee' => '1',
  'fee_type' => 'CNY',
  'is_subscribe' => 'Y',
  'mch_id' => '1470580002',
  'nonce_str' => 'cwvo3x1fev15vsuevvy91rfimpbtvjvb',
  'openid' => 'o84d-1AD_ZDNE9uwPmcvgKfprX6o',
  'out_trade_no' => '20201706011711303962',
  'result_code' => 'SUCCESS',
  'return_code' => 'SUCCESS',
  'sign' => '26BDA6CA14B305A3661EEBF5E20A027D',
  'time_end' => '20170601171145',
  'total_fee' => '1',
  'trade_type' => 'JSAPI',
  'transaction_id' => '4008022001201706013713791401',
         */
        if (!array_key_exists('result_code', $data)
            || !array_key_exists('return_code', $data)
            || !array_key_exists('transaction_id', $data)
            || !array_key_exists('total_fee', $data)
            || !array_key_exists('out_trade_no', $data)
        ) {
            return false;
        }
        if ($data['result_code'] != 'SUCCESS' || $data['result_code'] != 'SUCCESS') {
            return false;
        }
        $order_no = $data['out_trade_no'];
        $fee = $data['total_fee'];//分
        $transaction_id = $data['transaction_id'];
        //
        return $this->payCallback(PayService::$Wxpay, $fee, $transaction_id, $order_no);

    }


    /**
     * 支付回调业务逻辑
     * @param $pay_type
     * @param $pay_amount
     * @param $out_trade_no
     * @param $order_no
     * @return bool
     */
    protected function payCallback($pay_type, $pay_amount, $out_trade_no, $order_no)
    {
        if ($pay_type == PayService::$Alipay) {
            $pay_amount = intval($pay_amount * 100);//支付宝金额转换为分
        }
        $order_pre = substr($order_no, 0, 2);
        $result = false;
        switch ($order_pre) {
            case '10'://订单
                $result = $this->orderService->payOrder($order_no, $pay_amount, $out_trade_no, $pay_type);
                break;
            case '20'://充值
                $result = $this->orderService->payRechargeOrder($order_no, $pay_amount, $out_trade_no, $pay_type);
        }

        return $result;
    }

}