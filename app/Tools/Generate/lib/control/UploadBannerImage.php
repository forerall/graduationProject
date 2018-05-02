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
class UploadBannerImage extends BaseControl
{
    public function parseEdit(FieldConfig $fieldConfig)
    {
        $file = '_uploadBannerImage.blade.php';
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
        $file = '_uploadBannerImageShow.blade.php';
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
        $js = 'var jump_types = {
            0:\'不跳转\',
            1:\'url\',
            2:\'图片\',
            3:\'视频\',
            4:\'赛程\',
        }'.PHP_EOL;

        $js .= 'multiUploadBannerBox($(\'#'.$fieldConfig->key.'\'),\'/upload?'.$fieldConfig->extendParam.'\',{!! json_encode($item->'.$fieldConfig->key.') !!},80,jump_types);';
        return $js."\r\n";

    }
}