<?php
namespace App\Tools\Generate\config;

use App\Tools\Generate\lib\FieldType;

class FieldConfig
{

    public $displayWay;
    public $canShowInList;
    public $canFastEdit;
    public $canOrderBy;
    public $validate = [];//字段验证方式
    public $isPrimaryKey = false;
    public $key = '';//字段名
    public $name = '';//显示中文名
    public $controlType = '';//字段使用的控件 类型FieldType
    public $readonly = false;//是否只读,只读字段在编辑页不显示
    public $style = 'default';//显示风格
    public $extendParam = '';//ajax时额外参数
    public $searchLike = false; //字段是否是模糊匹配列
    public $fillable = true;//是否可以编辑
    public $dataSource;//下拉框数据来源,数组或 变量名
    //关联关系
    public $relation = [
        'belongsTo'=>[
            'model'=>'',//关联模型
            'foreignKey'=>'',//
            'ownerKey'=>'',//外键id
        ],//
        'hasMany'=>[
            'model'=>'',//关联模型
            'foreignKey'=>'',//本地id在中间表中的字段名
            'localKey'=>'',//关联模型id在中间表中的字段名
        ],//
        'belongsToMany'=>[
            'model'=>'',//关联模型
            'table'=>'',//关联中间表
            'foreignKey'=>'',//本地id在中间表中的字段名
            'relatedKey'=>'',//关联模型id在中间表中的字段名
        ],//
    ];
    public $relationType = '';

    public function needAppends(){

    }



    public function __construct($data = array())
    {
        $this->displayWay = '$item->{}';
        foreach($data as $key=>$val){
            if(property_exists($this,$key)){
                $this->$key = $val;
            }
        }
    }

    public static function primaryKey($name){
        return new static([
            'isPrimaryKey'=>true,
            'name'=>$name,
            'controlType'=>FieldType::$input,
            'canShowInList'=>true,
            'readonly'=>true,
            'fillable'=>false,
        ]);
    }
    public static function date($name){
        return new self([
            'name'=>$name,
            'controlType'=>FieldType::$date,
            'canShowInList'=>true,
            'fillable'=>true,
        ]);
    }
    public static function datelong($name){
        return new self([
            'name'=>$name,
            'controlType'=>FieldType::$datelong,
            'canShowInList'=>true,
            'fillable'=>true,
        ]);
    }
    public static function createdAt($name){
        return new self([
            'name'=>$name,
            'controlType'=>FieldType::$date,
            'canShowInList'=>true,
            'readonly'=>true,
            'fillable'=>false,
        ]);
    }
    public static function updatedAt($name){
        return new self([
            'name'=>$name,
            'controlType'=>FieldType::$date,
            'canShowInList'=>true,
            'readonly'=>true,
            'fillable'=>false,
        ]);
    }

    public static function normal($name){
        return new self([
           'name'=>$name,
            'controlType'=>FieldType::$input,
            'canShowInList'=>true,
            'readonly'=>false,
        ]);
    }
    public static function textarea($name){
        return new self([
            'name'=>$name,
            'controlType'=>FieldType::$textarea,
            'canShowInList'=>false,
            'readonly'=>false,
        ]);
    }

    /**
     * @param $name
     * @param $dataSource 固定选项时为数组，关联是为关联关系函数名
     * @return FieldConfig
     */
    public static function select($name,$dataSource){
        return new self([
            'name'=>$name,
            'controlType'=>FieldType::$select,
            'canShowInList'=>true,
            'readonly'=>false,
            'displayWay'=>'$item->{}_str',
            'dataSource'=>$dataSource,//有关联时 字符串形式
        ]);
    }

    /**
     * @param $name
     * @param $dataSource 固定选项时为数组，关联是为关联关系函数名
     * @return FieldConfig
     */
    public static function selectSearch($name,$dataSource){
        return new self([
            'name'=>$name,
            'controlType'=>FieldType::$selectSearch,
            'canShowInList'=>true,
            'readonly'=>false,
            'displayWay'=>'$item->{}_str',
            'dataSource'=>$dataSource,
        ]);
    }
    public static function multiSelect($name,$dataSource){
        return new self([
            'name'=>$name,
            'controlType'=>FieldType::$multiSelect,
            'canShowInList'=>false,
            'readonly'=>false,
            'dataSource'=>$dataSource,//字符串形式
        ]);
    }
    public static function file($name,$category = ''){
        return new self([
            'name'=>$name,
            'controlType'=>FieldType::$uploadFile,
            'canShowInList'=>false,
            'readonly'=>false,
            'extendParam'=>'category='.$category
        ]);
    }
    public static function multiImage($name,$category = ''){
        return new self([
            'name'=>$name,
            'controlType'=>FieldType::$multiUploadImage,
            'canShowInList'=>false,
            'readonly'=>false,
            'extendParam'=>'category='.$category
        ]);
    }
    public static function bannerImage($name,$category = ''){
        return new self([
            'name'=>$name,
            'controlType'=>FieldType::$uploadBannerImage,
            'canShowInList'=>false,
            'readonly'=>false,
            'extendParam'=>'category='.$category
        ]);
    }
    public static function image($name,$category = ''){
        return new self([
            'name'=>$name,
            'controlType'=>FieldType::$uploadImage,
            'canShowInList'=>false,
            'readonly'=>false,
            'extendParam'=>'category='.$category
        ]);
    }
    public static function imageWithCrop($name,$category = ''){
        return new self([
            'name'=>$name,
            'controlType'=>FieldType::$uploadImageWithCrop,
            'canShowInList'=>false,
            'readonly'=>false,
            'extendParam'=>'category='.$category
        ]);
    }
    public static function address($name){
        return new self([
            'name'=>$name,
            'controlType'=>FieldType::$address,
            'readonly'=>false,
        ]);
    }
    public static function editor($name){
        return new self([
            'name'=>$name,
            'controlType'=>FieldType::$editor,
            'readonly'=>false,
        ]);
    }


    //关联关系
    public function belongsTo($model,$ownerKey = 'id'){
        $this->relationType = 'belongsTo';
        $this->relation['belongsTo'] = [
            'model'=>$model,//关联模型
            'foreignKey'=>$this->key,//外键
            'ownerKey'=>$ownerKey,//关联表主键
        ];
        //存在验证
        $m = new $model();
        $m->getTable();
        $this->validate['exists:'.$m->getTable().','.$ownerKey] = $this->name.'不存在';
        //关联关系的dataSource
        $this->displayWay = '$item->'.$this->dataSource.'?$item->'.$this->dataSource.'->name:\'\'';
        return $this;
    }
    public function hasMany($model,$foreignKey = null,$localKey = null){
        $this->relationType = 'hasMany';
        $this->relation['hasMany'] = [
            'model'=>$model,//关联模型
            'foreignKey'=>$foreignKey,//本地id在中间表中的字段名
            'localKey'=>$localKey,//关联模型id在中间表中的字段名
        ];
        return $this;
    }
    public function belongsToMany($model,$table,$foreignKey = null,$relatedKey = null){
        $this->relationType = 'belongsToMany';
        $this->relation['belongsToMany'] = [
            'model'=>$model,//关联模型
            'table'=>$table,//关联中间表
            'foreignKey'=>$foreignKey,//本地id在中间表中的字段名
            'relatedKey'=>$relatedKey,//关联模型id在中间表中的字段名
        ];
        $this->fillable = false;
        return $this;
    }
    /////////////////////附加属性
    public function displayWay($display){
        $this->displayWay = $display;
        return $this;
    }
    public function searchLike(){
        $this->searchLike = true;
        return $this;
    }
    public function notShowInList(){
        $this->canShowInList = false;
        return $this;
    }
    public function readOnly(){
        $this->readonly = true;
        $this->fillable = false;
        return $this;
    }


    //字段验证
    public function required(){
        $this->validate['required'] = '不能为空';
        return $this;
    }
    public function phone(){
        $this->validate['phone'] = '不正确';
        return $this;
    }
    public function minLength($min){
        $this->validate['min:'.$min] = '最少'.$min.'字符';
        return $this;
    }
    public function integer($from = 0, $to = null){
        $this->validate['integer'] = '必须是整数';
        $this->validate['min:'.$from] = '最小值'.$from;
        if(!is_null($to)){
            $this->validate['max:'.$to] = '最大值'.$to;
        }
        return $this;
    }
    public function digits($len){
        $this->validate['digits:'.$len] = '必须'.$len.'位数字';
        return $this;
    }
    public function numeric(){
        $this->validate['numeric'] = '必须是数字类型';
        return $this;
    }
}