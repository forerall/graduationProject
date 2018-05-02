<?php
/**
 * Created by PhpStorm.
 * User: arts-mgcx
 * Date: 2017/1/24
 * Time: 10:49
 */

namespace App\Tools\Generate;


class Common
{

    /**
     * 目录检测，不存在则生成
     * @param $base
     * @param $dir
     * @return string
     * @throws \Exception
     */
    protected static function checkDir($base,$dir){
        //检测目录
        $dirs = explode('.', $dir);
        foreach ($dirs as $d) {
            if(empty($d)){
                continue;
            }
            $base = $base . DIRECTORY_SEPARATOR . $d;
            if (!is_dir($base)) {
                mkdir($base, 0733);
                chmod($base, 0733);
            }
        }
        if (!is_dir($base)) {
            throw new \Exception('目录创建失败：'.$base);
        }
        return $base;
    }
    public static function checkTplDir($dir){
        $base = resource_path('views');
        return self::checkDir($base,$dir);
    }
    public static function checkControllerDir($dir){
        $base = app_path('Http'.DIRECTORY_SEPARATOR.'Controllers');
        return self::checkDir($base,$dir);
    }
    public static function checkModelsDir($dir){
        $base = app_path('Http');
        return self::checkDir($base,$dir);
    }


    /**
     * 获取模板文件
     * @param $filename
     * @return string
     * @throws \Exception
     */
    protected static function getTpl($filename){
        $tplDir = __DIR__ . DIRECTORY_SEPARATOR . 'tpl'.DIRECTORY_SEPARATOR.$filename;
        if(file_exists($tplDir)){
            return file_get_contents($tplDir);
        }
        throw new \Exception('模板文件不存在:'.$filename);
    }
    //控件模板
    public static function getControlTpl($filename,$style = 'default'){
        return self::getTpl('control'.DIRECTORY_SEPARATOR.$style.DIRECTORY_SEPARATOR.$filename);
    }
    //curd 模板
    public static function getCurdTpl($filename,$style = 'default'){
        return self::getTpl('curd'.DIRECTORY_SEPARATOR.$style.DIRECTORY_SEPARATOR.$filename);
    }
    public static function getCurdController(){
        return self::getTpl('curd'.DIRECTORY_SEPARATOR.'Curd.php');
    }
    public static function getModel(){
        return self::getTpl('curd'.DIRECTORY_SEPARATOR.'Model.php');
    }


}