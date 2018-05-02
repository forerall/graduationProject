<?php
/**
 * Created by PhpStorm.
 * User: arts-mgcx
 * Date: 2017/1/23
 * Time: 17:13
 */

namespace App\Tools\Generate;

use App\Tools\Generate\config\CurdConfig;
use App\Tools\Generate\lib\DisplayGroup;
use App\Tools\Generate\lib\ControlGroup;

class CreateCurd
{
    public $pageConfig;

    //public $showBtnTpl = '<a class="showBtn" href="__URL__">查看</a>';
    //public $editBtnTpl = '<a class="editBtn" href="__URL__">编辑</a>';
    //public $delBtnTpl = '<a class="delBtn" href="javascript:void(0)">删除</a>';

    public $showBtnTpl = '<a class="blue showBtn" href="__URL__"><i class="icon-zoom-in bigger-130"></i></a>';
    public $editBtnTpl = '<a class="green editBtn" href="__URL__"><i class="icon-pencil bigger-130"></i></a>';
    public $delBtnTpl = '<a class="red delBtn" href="javascript:void(0)"><i class="icon-trash bigger-130"></i></a>';

    public function __construct(CurdConfig $testConfig)
    {
        $this->pageConfig = $testConfig;
    }

    //控制器
    public function controller()
    {
        //保存目录
        $dstDir = Common::checkControllerDir($this->pageConfig->classDir);
        //模板
        $tpl = Common::getCurdController();
        //
        $namespace = 'App\Http\Controllers';
        if ($this->pageConfig->classDir) {
            $namespace .= '\\' . str_replace('.', '\\', $this->pageConfig->classDir);
        }
        //
        $tpl = str_replace([
            '__Namespace__',
            '__ModelNamespace__',
            '__Class__',
            '__Model__',
            '__View__',
            '__StoreValidate__',
            '__UpdateValidate__',
            '__FillField__',
            '__LikeField__',
            '__With__',
            '__ViewWithData__',
            '__RelationSave__',
            '__RelationUpdate__',
            '__RelationDelete__',
        ], [
            $namespace,
            $this->controllerModelNamespace(),
            $this->pageConfig->className,
            $this->pageConfig->model,
            $this->pageConfig->templateDir,
            $this->storeValidate(),
            $this->updateValidate(),
            $this->fillFields(),
            $this->likeField(),
            $this->relationWith(),
            $this->relationViewWithData(),
            $this->relationSave(),
            $this->relationUpdate(),
            $this->relationDelete(),
        ], $tpl);
        file_put_contents($dstDir . DIRECTORY_SEPARATOR . $this->pageConfig->className . '.php', $tpl);
    }

    //列表页
    public function listPage()
    {
        //保存目录
        $dstDir = Common::checkTplDir($this->pageConfig->templateDir);
        //模板
        $filename = 'list.blade.php';
        $tpl = Common::getCurdTpl($filename, $this->pageConfig->style);
        //
        $tpl = str_replace([
            '__CREATEBTN__',
            '__Layout__',
            '__Name__',
            '__Field__',
            '__Item__',
            '__NoRecord__',
            '__Javascript__',
            '__Model__',
            '__BaseRoute__',
        ], [
            $this->createBtn(),
            $this->pageConfig->templateLayout,
            $this->pageConfig->name,
            $this->field(),
            $this->item(),
            $this->noRecord(),
            $this->javascript(),
            strtolower($this->pageConfig->model),
            $this->pageConfig->baseRoute,
        ], $tpl);
        file_put_contents($dstDir . DIRECTORY_SEPARATOR . $filename, $tpl);
    }

    //编辑页
    public function updatePage()
    {
        $dstDir = Common::checkTplDir($this->pageConfig->templateDir);
        //创建文件
        $filename = 'update.blade.php';
        $tpl = Common::getCurdTpl($filename, $this->pageConfig->style);
        //
        $group = $this->inputGroup();
        $tpl = str_replace([
            '__Layout__',
            '__Name__',
            '__BaseRoute__',
            '__InputGroup__',
            '__Javascript__',
            '__Style__',
            '__ConfigJs__',
            '__Model__',
            '__BaseRoute__',
        ], [
            $this->pageConfig->templateLayout,
            $this->pageConfig->name,
            $this->pageConfig->baseRoute,
            $group['html'],
            $group['addition']['js'],
            $group['addition']['css'],
            $group['addition']['config'],
            strtolower($this->pageConfig->model),
            $this->pageConfig->baseRoute,
        ], $tpl);
        file_put_contents($dstDir . DIRECTORY_SEPARATOR . $filename, $tpl);
    }

    //详情页
    public function showPage()
    {
        $dstDir = Common::checkTplDir($this->pageConfig->templateDir);
        //创建文件
        $filename = 'show.blade.php';
        $tpl = Common::getCurdTpl($filename, $this->pageConfig->style);
        //
        $tpl = str_replace([
            '__Layout__',
            '__Name__',
            '__DisplayGroup__',
            '__Javascript__',
            '__Model__',
            '__BaseRoute__',
        ], [
            $this->pageConfig->templateLayout,
            $this->pageConfig->name,
            $this->displayGroup(),
            '',
            strtolower($this->pageConfig->model),
            $this->pageConfig->baseRoute,
        ], $tpl);
        file_put_contents($dstDir . DIRECTORY_SEPARATOR . $filename, $tpl);
    }

    //模型
    public function model()
    {
        //保存目录
        $dstDir = Common::checkModelsDir('Models');
        //模板
        $tpl = Common::getModel();
        $tpl = str_replace([
            '__Model__',
            '__Table__',
        ], [
            $this->pageConfig->model,
            $this->pageConfig->tableName,
        ], $tpl);
        file_put_contents($dstDir . DIRECTORY_SEPARATOR . $this->pageConfig->model . '.php', $tpl);
    }

    protected function modelAna()
    {
        $appends = [];
        $casts = [];
        $relations = [];
        $attributes = [];
        foreach ($this->pageConfig->tableField() as $k => $v) {

        }
    }

    protected function relationDelete()
    {
        $save = [];
        foreach ($this->pageConfig->tableField() as $field) {
            if ($field->relationType == 'belongsToMany') {
                $save[] = 'if($result){'
                    . '$item->' . $field->key . '()->sync([]);'
                    . '}';
            }
        }
        return implode("", $save);
    }

    protected function relationUpdate()
    {
        $save = [];
        foreach ($this->pageConfig->tableField() as $field) {
            if ($field->relationType == 'belongsToMany') {
                $save[] = 'if($result){'
                    . '$ids = explode(\',\',$request->get(\'' . $field->key . '\'));'
                    . '$ids = array_filter($ids, function ($v) {
                        return intval($v)>0;
                    });'
                    . '$ids = empty($ids)?[]:$ids;'
                    . 'if($ids){'
                    . '$ids = Area::whereIn(\'id\',$ids)->pluck(\'id\')->toArray();'
                    . '}'
                    . '$item->' . $field->key . '()->sync($ids);'
                    . '}';
            }
        }
        return implode("", $save);
    }

    protected function relationSave()
    {
        $save = [];
        foreach ($this->pageConfig->tableField() as $field) {
            if ($field->relationType == 'belongsToMany') {
                $save[] = 'if($result){'
                    . '$ids = explode(\',\',$request->get(\'' . $field->key . '\'));'
                    . '$ids = array_filter($ids, function ($v) {
                        return intval($v)>0;
                    });'
                    . '$ids = empty($ids)?[]:$ids;'
                    . 'if($ids){'
                    . '$ids = Area::whereIn(\'id\',$ids)->pluck(\'id\')->toArray();'
                    . '}'
                    . '$item->' . $field->key . '()->attach($ids);'
                    . '}';
            }
        }
        return implode("", $save);
    }

    protected function relationWith()
    {
        /**
         * 列表查询时with()
         */
        $with = [];
        foreach ($this->pageConfig->tableField() as $field) {
            if ($field->relationType == 'belongsToMany') {
                $with[] = "\"" . $field->dataSource . "\"";
            } else if ($field->relationType == 'belongsTo') {
                $with[] = "\"" . $field->dataSource . "\"";
            }
        }
        if (empty($with)) return '';
        return '$list->with([' . implode(',', $with) . ']);' . PHP_EOL;
    }

    protected function relationViewWithData()
    {
        /*
         * 编辑时的数据输出
         * 通过with()传输数据到view
        */
        $with = [];
        foreach ($this->pageConfig->tableField() as $field) {
            if ($field->relationType == 'belongsToMany') {
                $model = $field->relation[$field->relationType]['model'];
                $model = substr($model, strrpos($model, '\\') + 1);
                $name = str_replace('$', '', $field->dataSource) . 's';
                $with[] = PHP_EOL . "->with('" . $name . "'," . $model . "::all())";
            } else if ($field->relationType == 'belongsTo') {
                $model = $field->relation[$field->relationType]['model'];
                $model = substr($model, strrpos($model, '\\') + 1);
                $name = str_replace('$', '', $field->dataSource) . 's';
                $with[] = PHP_EOL . "->with('" . $name . "'," . $model . "::all())";
            }
        }
        return implode("", $with);
    }

    protected function controllerModelNamespace()
    {
        $models = ['use ' . $this->pageConfig->modelNamespace . ';'];
        foreach ($this->pageConfig->tableField() as $field) {
            if ($field->relationType) {
                $models[] = 'use ' . $field->relation[$field->relationType]['model'] . ';';
            }
        }
        return implode(PHP_EOL, $models);

    }

    //控制器
    protected function storeValidate()
    {
        $tpl = '$this->validate($request,[' . PHP_EOL;
        foreach ($this->pageConfig->tableField() as $k => $v) {
            if (!empty($v->validate)) {
                $tpl .= "'{$v->key}'=>'" . implode('|', array_keys($v->validate)) . "'," . PHP_EOL;
            }
        }
        $tpl .= '],[' . PHP_EOL;
        foreach ($this->pageConfig->tableField() as $k => $v) {
            if (!empty($v->validate)) {
                foreach ($v->validate as $validate => $tip) {
                    //提示键名出去:后面的参数
                    if(strpos($validate,':')){
                        $validate = substr($validate, 0, strpos($validate, ':'));
                    }
                    $tpl .= "'{$v->key}.{$validate}'=>'" . $v->name . $tip . "'," . PHP_EOL;
                }
            }
        }
        $tpl .= ']);';
        return $tpl;
    }

    protected function updateValidate()
    {
        $tpl = '$this->validate($request,[' . PHP_EOL;
        foreach ($this->pageConfig->tableField() as $k => $v) {
            if (!empty($v->validate)) {
                $tpl .= "'{$v->key}'=>'sometimes|" . implode('|', array_keys($v->validate)) . "'," . PHP_EOL;
            }
        }
        $tpl .= '],[' . PHP_EOL;
        foreach ($this->pageConfig->tableField() as $k => $v) {
            if (!empty($v->validate)) {
                foreach ($v->validate as $validate => $tip) {
                    //提示键名出去:后面的参数
                    $validate = substr($validate, 0, strpos($validate, ':'));
                    $tpl .= "'{$v->key}.{$validate}'=>'" . $v->name . $tip . "'," . PHP_EOL;
                }
            }
        }
        $tpl .= ']);';
        return $tpl;
    }

    protected function fillFields()
    {
        $f = [];
        foreach ($this->pageConfig->tableField() as $k => $v) {
            if ($v->fillable) {
                $f[] = '\'' . $k . '\'';
            }
        }
        return implode(',', $f);
    }

    protected function likeField()
    {//获取模糊匹配字段
        foreach ($this->pageConfig->tableField() as $k => $v) {
            if ($v->searchLike) {
                return $v->key;
            }
        }
        return 'id';
    }

    //列表页面
    protected function createBtn()
    {
        if (in_array('createBtn', $this->pageConfig->operation)) {
            $html = '';
            $html .= '<div class="col-xs-12 r-toolbar">' . PHP_EOL;
            //$html .= '@if (Auth::guard(\'admin\')->user()->can(\'create\', \App\Models\\' . $this->pageConfig->model . '::class))' . PHP_EOL;
            $html .= '<a href="' . $this->pageConfig->baseRoute . '/create" class="btn btn-sm">新增</a>' . PHP_EOL;
            //$html .= '@endif' . PHP_EOL;
            $html .= '</div>';
            return $html;
        }
        return '';
    }

    protected function field()
    {
        $html = '';
        foreach ($this->pageConfig->tableField() as $field) {
            if ($field->canShowInList) {
                $html .= '<th><div>' . $field->name . '</div></th>' . PHP_EOL;
            }
        }
        if (count($this->pageConfig->operation) > 0) {
            $html .= '<th><div>操作</div></th>';
        }
        return $html;
    }

    protected function item()
    {
        $html = '<tr data-item-id="{{$item->id}}">';
        foreach ($this->pageConfig->tableField() as $key => $field) {
            if (!$field->canShowInList) {
                continue;
            }
            $v = str_replace('{}', $field->key, $field->displayWay);
            $html .= '<td><div>{{' . $v . '}}</div></td>' . PHP_EOL;
        }
        if (count($this->pageConfig->operation) > 0) {
            $html .= "<td width=\"" . (count($this->pageConfig->operation) * 30) . "\">" . PHP_EOL . "<div class=\"visible-md visible-lg hidden-sm hidden-xs action-buttons\">" . PHP_EOL;
            foreach ($this->pageConfig->operation as $operation) {
                switch ($operation) {
                    case 'editBtn':
                        $html .= str_replace('__URL__', $this->pageConfig->baseRoute . '/{{$item->id}}/edit', $this->editBtnTpl);
                        $html .= PHP_EOL;
                        break;
                    case 'showBtn';
                        $html .= str_replace('__URL__', $this->pageConfig->baseRoute . '/{{$item->id}}', $this->showBtnTpl);
                        $html .= PHP_EOL;
                        break;
                    case 'delBtn';
                        $html .= $this->delBtnTpl;
                        $html .= PHP_EOL;
                        break;
                }
            }
            $html .= "</div>" . PHP_EOL . "</td>";
        }
        $html .= '</tr>';

        return $html;
    }

    protected function noRecord()
    {
        $col = count($this->pageConfig->tableField());
        if (count($this->pageConfig->operation) > 0) {
            $col++;
        }
        return '<td colspan="' . $col . '" class="no-record">没有记录！</td>';
    }

    protected function javascript()
    {
        $js = '';
        foreach ($this->pageConfig->operation as $operation) {
            switch ($operation) {
                case 'delBtn':
                    $js .= '$(".delBtn").click(function(){'
                        . 'var id = $(this).closest("tr").data("item-id");'
                        . 'ajaxConfirm("{{url(\'' . $this->pageConfig->baseRoute . '\')}}/"+id,{},"DELETE",function(data){'
                        . 'location.reload();'
                        . '},"确认删除");'
                        . '});';
                    break;
            }
        }
        return $js;

    }

    //编辑页面 控件
    protected function inputGroup()
    {
        $html = '';
        ControlGroup::parseBegin();//开始生成代码
        foreach ($this->pageConfig->tableField() as $key => $field) {
            if ($field->readonly) {
                continue;
            }
            $group = ControlGroup::parseGroup($field);
            $html .= $group . PHP_EOL;
        }
        return [
            'html' => $html,
            'addition' => ControlGroup::parseEnd(),//获取依赖js
        ];
    }

    //详情页面 控件
    protected function displayGroup()
    {
        $html = '';
        foreach ($this->pageConfig->tableField() as $key => $field) {
            $group = DisplayGroup::parseGroup($field);
            $html .= $group . PHP_EOL;
        }
        return $html;
    }

}