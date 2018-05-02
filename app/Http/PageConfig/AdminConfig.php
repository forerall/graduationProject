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
class AdminConfig extends CurdConfig
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
            'name'=>FieldConfig::normal('账号')->required()->searchLike(),
            'email'=>FieldConfig::normal('邮箱'),
            'avatar'=>FieldConfig::image('头像','avatar'),
            'password'=>FieldConfig::normal('密码'),
            'created_at' => FieldConfig::createdAt('创建时间'),
            'updated_at' => FieldConfig::updatedAt('更新时间')->notShowInList(),
        ];
        foreach ($field as $key => $val) {
            $val->key = $key;
        }
        return $field;
    }

    public $name = '管理员';
    public $baseRoute = '/backstage/admin';//路由地址
    public $modelNamespace = 'App\Models\Admin';//模型
    public $model = 'Admin';//模型
    public $templateLayout = 'backstage.layouts.body';//模板地址
    public $templateDir = 'backstage.admin';//模板地址
    public $className = 'AdminController';//控制器名称
    public $classDir = 'Backstage';// 控制器生成目录，http/controllers下的目录
    public $style = 'style1';
}