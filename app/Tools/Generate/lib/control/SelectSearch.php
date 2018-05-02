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
class SelectSearch extends BaseControl
{
    public function parseEdit(FieldConfig $fieldConfig){
        $file = '_selectSearch.blade.php';
        $tpl = Common::getControlTpl($file);
        return str_replace([
            '__Key__',
            '__FieldName__',
            '__Option__',
        ],[
            $fieldConfig->key,
            $fieldConfig->name,
            $this->options($fieldConfig->key,$fieldConfig->dataSource),
        ],$tpl);
    }

    public function parseDisplay(FieldConfig $fieldConfig){
        $file = '_selectSearchShow.blade.php';
        $tpl = Common::getControlTpl($file);
        $value = str_replace('{}',$fieldConfig->key,$fieldConfig->displayWay);
        return str_replace([
            '__Value__',
            '__FieldName__',
        ],[
            $value,
            $fieldConfig->name,
        ],$tpl);
    }
    public function configJs(FieldConfig $fieldConfig)
    {
        $js = '$(\'select[name="'.$fieldConfig->key.'"]\').chosen();';
        return $js."\r\n";
    }
}