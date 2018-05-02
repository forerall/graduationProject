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
class ControlGroup
{
    protected static $parsing = false;
    protected static $controlStack = [];
    protected static $configJs = '';

    //引入依赖文件
    public static function parseBegin(){
        self::$configJs = '';
        self::$parsing = true;
        self::$controlStack = [];
    }
    public static function parseEnd(){
        $ret = ['js'=>'','css'=>'','config'=>''];
        if(self::$parsing){
            $js = '';
            $css = '';
            foreach(array_unique(self::$controlStack) as $control){
                $js .= ControlJs::getJs($control);
                $css .= ControlJs::getCss($control);
            }
            $ret = ['js'=>$js,'css'=>$css,'config'=>self::$configJs];
            self::$configJs = '';
            self::$parsing = true;
            self::$controlStack = [];
            return $ret;
        }
        return $ret;
    }

    //编译控件代码
    public static function parseGroup($fieldConfig){
        $control = null;
        $class = 'App\\Tools\\Generate\\lib\\control\\'.$fieldConfig->controlType;
        if(class_exists($class)){
            $control = new $class();
        }else{
            throw new \Exception('控件类不存在：'.$fieldConfig->controlType);
        }
        self::$controlStack[] = $fieldConfig->controlType;
        self::$configJs .= $control->configJs($fieldConfig);
        return $control->parseEdit($fieldConfig);
    }


}