<?php
/**
 * Created by PhpStorm.
 * User: arts-mgcx
 * Date: 2016/10/24
 * Time: 11:47
 */
namespace App\Tool\Vendor;

use Illuminate\Support\Facades\Lang;

class Validate
{
    /**
     * 身份证验证
     * @param $cardNo
     * @return bool
     */
    public static function isIdCard($cardNo){
        $vCity = array(
            '11','12','13','14','15','21','22',
            '23','31','32','33','34','35','36',
            '37','41','42','43','44','45','46',
            '50','51','52','53','54','61','62',
            '63','64','65','71','81','82','91'
        );
        if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $cardNo)) return false;
        if (!in_array(substr($cardNo, 0, 2), $vCity)) return false;
        $cardNo = preg_replace('/[xX]$/i', 'a', $cardNo);
        $vLength = strlen($cardNo);
        if ($vLength == 18) {
            $vBirthday = substr($cardNo, 6, 4) . '-' . substr($cardNo, 10, 2) . '-' . substr($cardNo, 12, 2);
        } else {
            $vBirthday = '19' . substr($cardNo, 6, 2) . '-' . substr($cardNo, 8, 2) . '-' . substr($cardNo, 10, 2);
        }

        if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
        if ($vLength == 18) {
            $vSum = 0;
            for ($i = 17 ; $i >= 0 ; $i--) {
                $vSubStr = substr($cardNo, 17 - $i, 1);
                $vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr , 11));
            }
            if($vSum % 11 != 1) return false;
        }
        return true;
    }
}