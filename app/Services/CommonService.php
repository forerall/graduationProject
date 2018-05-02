<?php
/**
 * Created by PhpStorm.
 * User: ygy
 * Date: 2017/6/5
 * Time: 17:45
 */

namespace App\Services;


use Carbon\Carbon;

class CommonService
{
    public static function intToDecimalMoney($intMoney)
    {
        return number_format($intMoney / 100, 2, '.', '');
    }

    public static function decimalToIntMoney($decimalMoney)
    {
        return intval($decimalMoney * 100);
    }

    public static $defaultProductImage = '';

    public static function countStr($count)
    {
        if ($count < 1000) {
            return $count;
        } else if ($count < 10000) {
            return number_format($count / 1000, 1, '.', '') . '千';
        } else {
            return number_format($count / 10000, 1, '.', '') . '万';
        }
    }

    public static function timeShort(Carbon $time)
    {
        $result = '';
        $minutes = intval(ceil((time() - $time->getTimestamp()) / 60));
        if($minutes <=0){
            $result = '刚刚';
        }else if ($minutes < 60) {
            $result = $minutes . '分钟前';
        } else {
            $hour = intval(ceil($minutes / 60));
            if ($hour < 24) {
                $result = $hour . '小时前';
            } else {
                $day = intval(ceil($hour / 24));
                if ($day < 30) {
                    $result = $day . '天前';
                } else {
                    $result = substr($time, 0, 16);
                }
            }
        }
        return $result;
    }
}