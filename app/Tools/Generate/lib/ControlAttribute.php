<?php
/**
 * Created by PhpStorm.
 * User: arts-mgcx
 * Date: 2017/1/24
 * Time: 16:31
 */

namespace App\Tools\Generate\lib;

/**
 * 获取控件需要的字段转换形式，
 * 用于Model的生成
 * Class ControlAttribute
 * @package App\Tools\Generate\lib
 */
class ControlAttribute
{
    protected static $basePath = '/box';
    protected static $control = [
        'Input'=>[
            'css'=>[
            ],
            'js'=>[
            ]
        ],
        'Textarea'=>[
            'css'=>[
            ],
            'js'=>[
            ]
        ],
        'Select'=>[
            'css'=>[
            ],
            'js'=>[
            ]
        ],
        'SelectSearch'=>[
            'css'=>[
                '/plug-in/chosen/chosen.css'
            ],
            'js'=>[
                '/plug-in/chosen/chosen.jquery.min.js'
            ]
        ],
        'Date'=>[
            'css'=>[

            ],
            'js'=>[
                '/plug-in/My97Date/WdatePicker.js',
            ]
        ],
        'DateLong'=>[
            'css'=>[

            ],
            'js'=>[
                '/plug-in/My97Date/WdatePicker.js',
            ]
        ],
        'UploadImage'=>[
            'css'=>[

            ],
            'js'=>[
                '/plug-in/upload/jquery-ui.min.js',
                '/plug-in/upload/jquery.fileupload.js',
            ]
        ],
        'MultiUploadImage'=>[
            'css'=>[

            ],
            'js'=>[
                '/plug-in/upload/jquery-ui.min.js',
                '/plug-in/upload/jquery.fileupload.js',
            ]
        ],
        'UploadBannerImage'=>[
            'css'=>[

            ],
            'js'=>[
                '/plug-in/upload/jquery-ui.min.js',
                '/plug-in/upload/jquery.fileupload.js',
            ]
        ],
        'UploadFile'=>[
            'css'=>[

            ],
            'js'=>[
                '/plug-in/upload/jquery-ui.min.js',
                '/plug-in/upload/jquery.fileupload.js',
            ]
        ],
        'UploadImageWithCrop'=>[
            'css'=>[
                '/plug-in/Jcrop/css/jquery.Jcrop.min.css',
            ],
            'js'=>[
                '/plug-in/upload/jquery-ui.min.js',
                '/plug-in/upload/jquery.fileupload.js',
                '/plug-in/Jcrop/js/jquery.Jcrop.min.js',
            ]
        ],
        'Address'=>[
            'css'=>[

            ],
            'js'=>[
                '/plug-in/area/area.js',
            ]
        ],
        'Editor'=>[
            'css'=>[

            ],
            'js'=>[
                '/plug-in/ueditor/ueditor.config.js',
                '/plug-in/ueditor/ueditor.all.min.js',
                '/plug-in/ueditor/lang/zh-cn/zh-cn.js',
            ]
        ],
        'MultiSelect'=>[
            'css'=>[
                '/plug-in/chosen/chosen.css',
            ],
            'js'=>[
                '/plug-in/chosen/chosen.jquery.min.js',
            ]
        ],
    ];

    public static function getCss($control){
        if(!isset(self::$control[$control])){
            throw new \Exception('控件配置没有找到：'.$control);
        }
        $setting = self::$control[$control]['css'];
        $css = '';
        foreach($setting as $val){
            $css .= '<link rel="stylesheet"  type="text/css" href="'.self::$basePath.$val.'" />';
            $css .= "\r\n";
        }
        return $css;

    }
    public static function getJs($control){
        if(!isset(self::$control[$control])){
            throw new \Exception('控件配置没有找到：'.$control);
        }
        $setting = self::$control[$control]['js'];
        $js = '';
        foreach($setting as $val){
            $js .= ' <script type="text/javascript" src="'.self::$basePath.$val.'"></script>';
            $js .= "\r\n";
        }
        return $js;
    }

}