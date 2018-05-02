<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'g_admin';
    protected $guarded = [];
    protected $appends = ['type_str'];
    public static $typeArray = [
        0 => '管理员',
        1 => '超级管理员',
    ];

    public function getTypeStrAttribute(){
        if(array_key_exists($this->type,self::$typeArray)){
            return self::$typeArray[$this->type];
        }
        return '';
    }
    public function isAdmin()
    {
        return $this->id == 1;
    }
}
