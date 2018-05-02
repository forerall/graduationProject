<?php
namespace App\Tools\Sms;

use App\Tools\Output;

/**
 * Created by PhpStorm.
 * User: arts-mgcx
 * Date: 2017/3/27
 * Time: 14:11
 */
class Sms
{
    public function send($phone, $data, $type)
    {
        $r = Wangyi::send($phone, $data);
        if (isset($r['code']) && $r['code'] == 200) {
            return Output::Success('发送成功');
        }
        return Output::Fail('发送失败');
    }
}