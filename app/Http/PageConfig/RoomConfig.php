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
class RoomConfig extends CurdConfig
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
            'packets'=>FieldConfig::normal('包数')->required(),
            'peilv'=>FieldConfig::normal('赔率')->required(),
            'min'=>FieldConfig::normal('最小金额')->required(),
            'max'=>FieldConfig::normal('最大金额')->required(),
            'peilv'=>FieldConfig::normal('赔率')->required(),
            'state'=>FieldConfig::select('状态',$model.'::$stateArray'),
            'created_at' => FieldConfig::createdAt('创建时间'),
            'updated_at' => FieldConfig::updatedAt('更新时间')->notShowInList(),
        ];
        foreach ($field as $key => $val) {
            $val->key = $key;
        }
        return $field;
    }

    public $name = '房间';
    public $baseRoute = '/backstage/room';//路由地址
    public $modelNamespace = 'App\Models\Room';//模型
    public $model = 'Room';//模型
    public $templateLayout = 'backstage.layouts.body';//模板地址
    public $templateDir = 'backstage.room';//模板地址
    public $className = 'RoomController';//控制器名称
    public $classDir = 'Backstage';// 控制器生成目录，http/controllers下的目录
    public $style = 'style1';
}