<?php
namespace App\Http\PageConfig;

use App\Models\Admin;
use App\Models\Area;
use App\Tools\Generate\config\CurdConfig;
use App\Tools\Generate\config\FieldConfig;

/**
 * Created by PhpStorm.
 * User: arts-mgcx
 * Date: 2017/1/23
 * Time: 16:39
 */
class BannerConfig extends CurdConfig
{

    //列表操作
    public $operation = [
        'showBtn', 'editBtn', 'delBtn', 'createBtn'
    ];

    //列表字段
    public function tableField()
    {
        $model = $this->modelNamespace;
        $field = [
            'id' => FieldConfig::primaryKey('ID'),
            'name'=>FieldConfig::normal('名称')->required()->searchLike(),
            'type'=>FieldConfig::normal('类型')->required(),
            'state'=>FieldConfig::select('状态',$model.'::$stateArray'),
            'images'=>FieldConfig::bannerImage('轮播图','banner'),
            'created_at' => FieldConfig::createdAt('创建时间'),
            'updated_at' => FieldConfig::updatedAt('更新时间')->notShowInList(),
        ];
        foreach ($field as $key => $val) {
            $val->key = $key;
        }
        return $field;
    }

    public $name = '轮播图';
    public $baseRoute = '/backstage/banner';//路由地址
    public $modelNamespace = 'App\Models\Banner';//模型
    public $model = 'Banner';//模型
    public $templateLayout = 'backstage.layouts.body';//模板地址
    public $templateDir = 'backstage.banner';//模板地址
    public $className = 'BannerController';//控制器名称
    public $classDir = 'Backstage';// 控制器生成目录，http/controllers下的目录
    public $style = 'style1';
}