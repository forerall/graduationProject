<?php

namespace App\Tools\Generate\lib\control;
use App\Tools\Generate\Common;
use App\Tools\Generate\config\FieldConfig;
/**
 * Created by PhpStorm.
 * User: arts-mgcx
 * Date: 2017/2/4
 * Time: 9:40
 */
class MultiUploadImage extends BaseControl
{
    public function parseEdit(FieldConfig $fieldConfig)
    {
        $file = '_multiUploadImage.blade.php';
        $tpl = Common::getControlTpl($file);
        return str_replace([
            '__Key__',
            '__FieldName__',
        ], [
            $fieldConfig->key,
            $fieldConfig->name,
        ], $tpl);
    }

    public function parseDisplay(FieldConfig $fieldConfig)
    {
        $file = '_multiUploadImageShow.blade.php';
        $tpl = Common::getControlTpl($file);
        $value = str_replace('{}',$fieldConfig->key,$fieldConfig->displayWay);
        return str_replace([
            '__Value__',
            '__FieldName__',
        ], [
            $value,
            $fieldConfig->name,
        ], $tpl);
    }
    public function configJs(FieldConfig $fieldConfig)
    {
        $js = 'multiUploadImageBox($(\'#'.$fieldConfig->key.'\'),\'/upload?'.$fieldConfig->extendParam.'\',{!! json_encode($item->'.$fieldConfig->key.') !!},80);';
        return $js."\r\n";

    }
}