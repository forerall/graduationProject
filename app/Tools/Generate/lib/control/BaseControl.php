<?php

namespace App\Tools\Generate\lib\control;

use App\Tools\Generate\config\FieldConfig;

/**
 * Created by PhpStorm.
 * User: arts-mgcx
 * Date: 2017/2/4
 * Time: 9:40
 */
class BaseControl
{
    public function parseEdit(FieldConfig $config)
    {

    }

    public function parseDisplay(FieldConfig $config)
    {

    }

    public function configJs(FieldConfig $config)
    {
        return '';
    }

    /**
     * 下拉选择框数据构造
     * @param $key
     * @param $arr 数组或者变量名称
     * @return string
     */
    protected function options($key, $arr)
    {
        $op = '';
        if (is_string($arr)) {
            //有:: 的表示Model::$arr的选项
            if(strpos($arr,'::')===false){
                $optionKey = '$' . $arr . '->id';
                $optionVal = '$' . $arr . '->name';
                $op .= '@forelse($' . $arr . 's as $' . $arr . ')' . "\r\n"
                    . '<option value="{{' . $optionKey . '}}"{{$item->' . $key . '==' . $optionKey . '? \' selected="selected"\':\'\'}}>{{' . $optionVal . '}}</option>' . "\r\n"
                    . '@empty' . "\r\n"
                    . '@endforelse';
            }else{
                $op .= '@forelse(' . $arr . ' as $k=>$v)' . "\r\n"
                    . '<option value="{{$k}}"{{$item->' . $key . '==$k? \' selected="selected"\':\'\'}}>{{$v}}</option>' . "\r\n"
                    . '@empty' . "\r\n"
                    . '@endforelse';
            }

        } else if (is_array($arr)) {
            foreach ($arr as $k => $v) {
                $op .= '<option value="' . $k . '"{{$item->' . $key . '==' . $k . '?\' selected="selected"\':\'\'}}>' . $v . '</option>';
                $op .= "\r\n";
            }
        }

        return $op;
    }
}