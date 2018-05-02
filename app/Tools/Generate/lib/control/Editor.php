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
class Editor extends BaseControl
{
    public function parseEdit(FieldConfig $fieldConfig)
    {
        $file = '_editor.blade.php';
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
        $file = '_editorShow.blade.php';
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
        $js = 'var ue = UE.getEditor(\''.$fieldConfig->key.'\');';
        $js .= "\r\n";
        $js .= 'ue.addListener( \'ready\', function( editor ) {';
        $js .= "\r\n";
        $js .= 'UE.getEditor(\''.$fieldConfig->key.'\').setContent($(\'#'.$fieldConfig->key.'_hidden_content\').html(), false);';
        $js .= "\r\n";
        $js .= '} );';
        $js .= "\r\n";
        $js .= 'ue.addListener("contentChange",function(){';
        $js .= "\r\n";
        $js .= '$(\'input[name="'.$fieldConfig->key.'"]\').val(UE.getEditor(\''.$fieldConfig->key.'\').getContent());';
        $js .= "\r\n";
        $js .= '});';
        $js .= "\r\n";
        return $js;

    }
}