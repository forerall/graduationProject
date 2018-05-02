<?php
namespace App\Tools\Generate\lib;
use App\Tools\Generate\lib\control\Address;
use App\Tools\Generate\lib\control\Date;
use App\Tools\Generate\lib\control\Editor;
use App\Tools\Generate\lib\control\Input;
use App\Tools\Generate\lib\control\Select;
use App\Tools\Generate\lib\control\UploadImage;

/**
 * Created by PhpStorm.
 * User: arts-mgcx
 * Date: 2017/1/24
 * Time: 9:47
 */
class DisplayGroup
{
    public static function parseGroup($fieldConfig){
        $control = null;
        $class = 'App\\Tools\\Generate\\lib\\control\\'.$fieldConfig->controlType;
        if(class_exists($class)){
            $control = new $class();
        }else{
            throw new \Exception('控件类不存在：'.$fieldConfig->controlType);
        }
        return $control->parseDisplay($fieldConfig);
    }

}