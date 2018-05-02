<?php
namespace App\Tools\Wechat\Weixin\lib;

class Menu{
    public $name;
    public $type;
    public $sub_menu;
    public $key;
    public $url;
    public $media_id;

    public function __construct($type)
    {
        $this->type = $type;
        $this->sub_menu = [];
    }

    public function addSubMenu($sub){
        $this->sub_menu[] = $sub;
    }


    //josn解析成对象
    public static function decode($menu){
        $buttonArray = [];//菜单数组
        if(isset($menu['menu'])&&isset($menu['menu']['button'])){
            foreach($menu['menu']['button'] as $btn){
                $sub = [];
                //有子菜单先解析成对象
                if(isset($btn['sub_button'])&&!empty(isset($btn['sub_button']))){
                    foreach($btn['sub_button'] as $sbtn){
                        $sub[] = Menu::parseMenu($sbtn);
                    }
                }
                $b = Menu::parseMenu($btn);
                if(!empty($sub)){
                    $b->sub_menu = $sub;
                }
                $buttonArray[] = $b;
            }
        }
        return $buttonArray;
    }
    //对象解析成json
    public static function encode($menu){
        $newButton = [];
        //对象转成数组
        $menu = json_encode($menu);
        $menu = json_decode($menu,true);
        //处理数组中的空值
        foreach($menu as $k=>$btn){
            //先处理二级菜单
            $newSub = [];
            if(isset($btn['sub_menu'])&&$btn['sub_menu']){
                foreach($btn['sub_menu'] as $sb){
                    //过滤数组的空值
                    $sb = array_filter($sb,function($v){return $v&&$v!='sub_menu';});
                    if($sb){
                        $newSub[] = $sb;
                    }
                }
            }
            //一级菜单存在添加到$newButton
            $btn = array_filter($btn,function($v){return $v&&$v!='sub_menu';});
            if($btn){
                $btn['sub_menu'] = $newSub;
                $newButton[] = $btn;
            }
        }
        return ['button'=>$newButton];
    }
    //数组解析成Menu对象，不支持子菜单
    public static function parseMenu($data){
        $menu = new static('');
        foreach($data as $key=>$val){
            if($key=='sub_button')continue;
            if(property_exists($menu,$key)){
                $menu->$key = $val;
            }
        }
        return $menu;
    }

    //view：跳转URL
    public static function viewBtn($name,$url){
        $btn = new static('view');
        $btn->name = $name;
        $btn->url = $url;
        return $btn;
    }
    //click：点击推事件
    public static function clickBtn($name,$key){
        $btn = new static('click');
        $btn->name = $name;
        $btn->key = $key;
        return $btn;
    }
}