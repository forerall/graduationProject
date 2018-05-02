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
class MultiSelect extends BaseControl
{
    public function parseEdit(FieldConfig $fieldConfig)
    {
        $file = '_multiSelect.blade.php';
        $tpl = Common::getControlTpl($file);

        return str_replace([
            '__Key__',
            '__FieldName__',
            '__Options__',
        ], [
            $fieldConfig->key,
            $fieldConfig->name,
            $this->mOptions($fieldConfig)
        ], $tpl);
    }

    public function parseDisplay(FieldConfig $fieldConfig)
    {

        $file = '_multiSelectShow.blade.php';
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
        $js = 'mulSelectInit(\'' . $fieldConfig->key . '\',\'{{implode(\',\',$item->' . $fieldConfig->key . '->pluck(\'id\')->all())}}\');';
        return $js . "\r\n";
    }

    protected function mOptions(FieldConfig $fieldConfig)
    {
        $itemName = '$' . $fieldConfig->key . '_item';
        $op = '@forelse($' .$fieldConfig->dataSource. 's as ' . $itemName . ')' . PHP_EOL
            . '<option value="{{' . $itemName . '->id}}">{{' . $itemName . '->name}}</option>' . PHP_EOL
            . '@empty' . PHP_EOL
            . '@endforelse' . PHP_EOL;

        return $op;
    }
}