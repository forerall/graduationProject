<?php
namespace App\Tools\Generate\lib;
/**
 * Created by PhpStorm.
 * User: arts-mgcx
 * Date: 2017/1/24
 * Time: 9:53
 */
class FieldType
{
    public static $input = 'Input';//输入框
    public static $editor = 'Editor';//编辑器
    public static $textarea = 'Textarea';//编辑器


    public static $select = 'Select';//下拉选择框
    public static $multiSelect = 'MultiSelect';//多选下拉框
    public static $selectSearch = 'SelectSearch';//下拉选择框带搜索

    public static $date = 'Date';//日期控件
    public static $datelong = 'DateLong';//日期控件,带时分秒

    public static $uploadImage = 'UploadImage';//图片上传
    public static $multiUploadImage = 'MultiUploadImage';//多图片上传
    public static $uploadImageWithCrop = 'UploadImageWithCrop';//图片上传,编辑裁剪
    public static $uploadBannerImage = 'UploadBannerImage';//轮播图

    public static $uploadFile = 'UploadFile';//文件上传
    public static $address = 'Address';//省市区

}