<?php
namespace App\Tools\Generate\config;

/**
 * Created by PhpStorm.
 * User: arts-mgcx
 * Date: 2017/1/23
 * Time: 16:39
 */
class CurdConfig
{

    //列表操作
    public $operation = [
        'editBtn', 'delBtn','showBtn'
    ];
    //列表字段
    public function tableField(){
        $model = $this->modelNamespace;
        return[
        ];
    }
    public $tableName = '';//表名
    public $name= '';//数据名称 如 用户
    public $baseRoute = '';//路由地址
    public $modelNamespace = '';//模型
    public $model = '';//模型名称
    public $templateLayout = '';//模板布局
    public $templateDir = '';//模板地址
    public $className = '';//控制器名称
    public $classDir = '';// 控制器生成目录，http/controllers下的目录
    public $style = 'default';//页面风格

}