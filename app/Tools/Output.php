<?php
/**
 * Created by PhpStorm.
 * User: arts-mgcx
 * Date: 2016/10/24
 * Time: 11:47
 */
namespace App\Tools;

use Illuminate\Support\Facades\Lang;

class Output
{

    public static function Unauthorized($status = 0)
    {
        return [
            'errCode' => 3,
            'errMsg' => '未登陆',
            'status' => $status,
            'data' => ''
        ];
    }

    /**
     * 返回成功
     * @param $errMsg
     * @param null $data
     * @param int $status
     * @return array
     */
    public static function Success($errMsg,$data = null,$status = 0){
        if(request('debug')){
            dd([
                'errCode'   =>  0,
                'errMsg'    =>  $errMsg,
                'status'    => $status,
                'data'      =>  empty($data)?(object)array():$data->toArray()
            ]);
        }
        return [
            'errCode'   =>  0,
            'errMsg'    =>  $errMsg,
            'status'    => $status,
            'data'      =>  empty($data)?(object)array():$data
        ];
    }

    /**
     * 返回失败
     * @param $errMsg
     * @param null $data
     * @param int $status
     * @return array
     */
    public static function Fail($errMsg,$data = null,$status = 0){
        if(request('debug')){
            dd([
                'errCode'   =>  1,
                'errMsg'    =>  $errMsg,
                'status'    => $status,
                'data'      =>  empty($data)?(object)array():$data
            ]);
        }
        return [
            'errCode'   =>  1,
            'errMsg'    =>  $errMsg,
            'status'    => $status,
            'data'      =>  empty($data)?(object)array():$data
        ];
    }

    /**
     * 参数错误 app 错误信息 =>val,web 错误信息 key=>val
     * @param null $data
     * @param int $status
     * @return array
     */
    public static function ParameterError($data = null,$status = 0){
        $error = [];
        if($data){
            if(request('web')){
                $error = $data;
            }else{
                foreach($data as $v){
                    if(is_array($v)){
                        foreach($v as $e){
                            $error[] = $e;
                        }
                    }else if(is_string($v)){
                        $error[] = $v;
                    }
                }
            }

        }
        if(request('debug')){
            dd([
                'errCode'   =>  2,
                'errMsg'    =>  '参数错误',
                'status'    => $status,
                'data'      =>  (object)['error'=>$error]
            ]);
        }

        return [
            'errCode'   =>  2,
            'errMsg'    =>  '',
            'status'    => $status,
            'data'      =>  (object)['error'=>$error]
        ];
    }


    public static function Result($state,$succMsg,$errMsg,$data = null){
        if($state>0){
            return self::Success($succMsg,$data);
        }else{
            return self::Fail($errMsg,$data);
        }
    }

}