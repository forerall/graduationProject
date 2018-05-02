<?php
/**
 * Created by PhpStorm.
 * User: arts-mgcx
 * Date: 2016/10/19
 * Time: 11:45
 */
namespace App\Services;


use App\Models\SmsRecord;
use App\Tools\Output;
use App\Tools\Sms\Sms;
use Illuminate\Support\Facades\Log;

//短信
class SmsService
{
    protected $expire = 15;//有效时间。分钟
    protected $sendSpan = 60;//发送间隔 。秒

    //登陆
    public function sendLoginCode($to)
    {
        return $this->sendSms($to, 'login');
    }

    public function checkLoginCode($phone, $code, $disable = true)
    {
        return $this->checkCode($phone, $code, 'login', $disable);
    }

    //注册
    public function sendRegisterCode($to)
    {
        return $this->sendSms($to, 'register');
    }

    public function checkRegisterCode($phone, $code, $disable = true)
    {
        return $this->checkCode($phone, $code, 'register', $disable);
    }

    //找回密码
    public function sendForgetPasswordCode($to)
    {
        return $this->sendSms($to, 'forgetPassword');
    }

    public function checkForgetPasswordCode($phone, $code, $disable = true)
    {
        return $this->checkCode($phone, $code, 'forgetPassword', $disable);
    }

    //更换手机号
    public function sendChangePhoneCode($to)
    {
        return $this->sendSms($to, 'changePhone');
    }

    public function checkChangePhoneCode($phone, $code, $disable = true)
    {
        return $this->checkCode($phone, $code, 'changePhone', $disable);
    }

    //第三方绑定
    public function sendBindThirdCode($to)
    {
        return $this->sendSms($to, 'bindThird');
    }

    public function checkBindThirdCode($phone, $code, $disable = true)
    {
        return $this->checkCode($phone, $code, 'bindThird', $disable);
    }


    /**
     * 发生验证码
     * @param $to
     * @param $type
     * @return array|int
     */
    protected function sendSms($to, $type)
    {
        if (preg_match("/1[34578]{1}\d{9}$/", $to)) {
        } else {
            return Output::Fail('手机号不正确');
        }
        try {
            //生成4位验证码
            $result = $this->generateCode($to, $type, 4);
            if ($result['errCode'] == 0) {
                $sms = new Sms();
                return $sms->send($to, $result['data'], $type);
            }
            return $result;
        } catch (\Exception $e) {
            Log::error($e);
            return Output::Fail('发送失败');
        }
    }

    /**
     * 检查验证码
     * @param $phone
     * @param $code
     * @param $type
     * @param $disabled
     * @return array
     */
    protected function checkCode($phone, $code, $type, $disabled = true)
    {
        if (config('app.testenv')) {
            return Output::Success('验证成功');
        }
        try {
            $record = SmsRecord::where('phone', $phone)
                ->where('type', $type)
                ->where('available', 1)
                ->first();
            if (is_null($record) || $record->expired_at < time()) {
                return Output::Fail('验证码已过期，请重新发送');
            }
            if ($record->code != $code) {
                return Output::Fail('验证码错误');
            }
            if ($disabled) {
                $record->available = 0;
                $record->save();
            }
            return Output::Success('验证成功');
        } catch (\Exception $e) {
            Log::error($e);
            return Output::Fail('系统错误');
        }

    }

    /**
     * 生成验证码
     * @param $phone
     * @param $type
     * @param int $len
     * @return int
     */
    protected function generateCode($phone, $type, $len = 4)
    {
        $minutes = $this->expire;//过期时间
        $record = SmsRecord::firstOrNew(['phone' => $phone]);
        if ($record->send_time + $this->sendSpan >= time()) {
            $s = max(1, $record->send_time + $this->sendSpan - time());
            return Output::Fail("发送太频繁，请在{$s}秒后再发");
        }
        $record->send_time = time();
        if ($record->available == 1 && $record->type == $type && $record->expired_at > time()) {
            $record->expired_at = time() + 60 * $minutes;
            $record->save();
            return Output::Success('发送成功', $record->code);
        }
        $record->code = mt_rand(pow(10, $len - 1), pow(10, $len) - 1);
        $record->type = $type;
        $record->available = 1;
        $record->expired_at = time() + 60 * $minutes;
        $record->save();
        return Output::Success('发送成功', $record->code);
    }
}