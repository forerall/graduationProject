<?php
/**
 * Created by PhpStorm.
 * User: arts-mgcx
 * Date: 2016/10/19
 * Time: 11:45
 */
namespace App\Services;

use App\Tools\Image\Image;
use Illuminate\Http\Request;

class UploadService
{
    protected $fileSystemDriver = 'uploads';
    protected $categoryName = 'category';
    protected $config = [
        ///系统设置
        'avatar' => [
            'path' => 'images/avatar',
            'type' => 'image',
            'size' => 4,
            'thumb' => true,
            'thumb_width' => [200],
            'thumb_height' => [200],
            'thumb_ext' => ['_s'],
            'thumb_way' => 'auto',
        ],
        'article' => [
            'path' => 'images/article',
            'type' => 'image',
            'size' => 4,
            'thumb' => true,
            'thumb_width' => [200],
            'thumb_height' => [200],
            'thumb_ext' => ['_s'],
            'thumb_way' => 'auto',
        ],
        'banner' => [
            'path' => 'images/banner',
            'type' => 'image',
            'size' => 4,
            'thumb' => true,
            'thumb_width' => [200,300,400],
            'thumb_height' => [200,300,400],
            'thumb_ext' => ['_s','_300','_400'],
            'thumb_way' => 'w',
        ],
        //
        'product' => [
            'path' => 'images/product',
            'type' => 'image',
            'size' => 4,
            'thumb' => true,
            'thumb_width' => [600],
            'thumb_height' => [600],
            'thumb_ext' => ['_s'],
            'thumb_way' => 'auto',
        ],
        'paipai' => [
            'path' => 'images/paipai',
            'type' => 'image',
            'size' => 4,
            'thumb' => true,
            'thumb_width' => [600],
            'thumb_height' => [600],
            'thumb_ext' => ['_s'],
            'thumb_way' => 'auto',
        ],
        'user' => [
            'path' => 'images/user',
            'type' => 'image',
            'size' => 4,
            'thumb' => true,
            'thumb_width' => [600],
            'thumb_height' => [600],
            'thumb_ext' => ['_s'],
            'thumb_way' => 'auto',
        ],
        'auth' => [
            'path' => 'images/auth',
            'type' => 'image',
            'size' => 4,

        ],
        'store' => [
            'path' => 'images/store',
            'type' => 'image',
            'size' => 4,

        ],
        'video' => [
            'path' => 'images/video',
            'type' => 'video',
            'size' => 4,

        ],
        //自定义表情
        'seflimg' => [
            'path' => 'images/seflimg',
            'type' => 'image',
            'size' => 4,

        ],
        //聊天图片
        'im' => [
            'path' => 'images/im',
            'type' => 'image',
            'size' => 4,

        ],
        //聊天语音
        'audio' => [
            'path' => 'im/audio',
            'type' => 'audio',
            'size' => 4,

        ],
    ];

    public function getConfig($category)
    {
        if (empty($category)) {
            throw new \Exception('category is empty');
        }
        if (!array_key_exists($category, $this->config)) {
            throw new \Exception('upload category config is empty');
        }
        return $this->config[$category];
    }

    public function uploadPath()
    {
        return 'uploads/';
    }

    public function rootPath()
    {
        return public_path() . '/';
    }

    /**
     * 上传一张图片
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function saveFiles(Request $request)
    {
        $category = $request->get($this->categoryName);
        $config = $this->getConfig($category);

        if ($request->files->count() <= 0) {
            throw new \Exception('upload file is empty');
        }
        $files = [];
        $path = '/' . $this->uploadPath() . $config['path'] . '/';
        foreach ($request->allFiles() as $file) {

            if($config['type']=='audio'){
                $ext = '.amr';
                if($file->getClientMimeType()=='audio/wav'){
                    $ext = '.wav';
                }
                $randomName = $file->hashName();
                $randomName = explode('.', $randomName)[0] . $ext;
                $save = $file->storeAs($config['path'], $randomName, $this->fileSystemDriver);
            }else{
                $save = $file->store($config['path'], $this->fileSystemDriver);
            }
            $index = strrpos($save, '/') + 1;
            $filename = $path . substr($save, $index);
            $files[] = [
                'filename' => $filename,
                'thumb' => [asset($filename)],
            ];
        }
        //生成缩略图
        if (array_key_exists('thumb', $config) && $config['thumb']) {
            foreach ($files as &$file) {
                $srcFile = $this->rootPath() . ltrim($file['filename'], '/');
                $thumbs = $this->thumb($srcFile, $config);
                array_walk($thumbs, function (&$thumb, $key, $path) {
                    $thumb = asset($path . $thumb);
                }, $path);
                $file['thumb'] = $thumbs;
            }
        }
        return $this->outputFormat($files, $config['type']);
    }

    /**
     * 生成图片缩略图
     * @param $srcFile 源文件
     * @param $config 配置
     * @return array 缩略图文件名数组
     * @throws \App\Tools\Image\ImageProcessException
     * @throws \Exception
     */
    public function thumb($srcFile, $config)
    {
        //缩略图
        if (!array_key_exists('thumb_way', $config)
            || !array_key_exists('thumb_width', $config)
            || !array_key_exists('thumb_height', $config)
            || !array_key_exists('thumb_ext', $config)
        ) {
            throw new \Exception('thumb config error');
        }
        $thumb_way = $config['thumb_way'];
        $width = $config['thumb_width'];
        $height = $config['thumb_height'];
        $ext = $config['thumb_ext'];

        $thumb_arr = [];
        $srcImage = Image::openFromFile($srcFile);
        foreach ($ext as $k => $v) {
            $newFile = substr($srcFile, 0, strrpos($srcFile, '.')) . $v . substr($srcFile, strrpos($srcFile, '.'));
            $newFileName = substr($newFile, strrpos($srcFile, '/') + 1);
            if ($thumb_way == 'w') {
                if ($width[$k] > $srcImage->width) {
                    $width[$k] = $srcImage->width;
                }
                $thumb = Image::thumbByWidth($srcImage, $width[$k], $height[$k]);
            } else {
                $thumb = Image::thumb($srcImage, $width[$k], $height[$k]);
            }
            $thumb->save($newFile);
            $thumb_arr[] = $newFileName;
        }
        $srcImage->destroy();
        return $thumb_arr;
    }

    /**
     * 生成图片水印
     * @param $category
     * @param $path
     * @param $file
     * @throws \App\Tools\Image\ImageProcessException
     * @throws \Exception
     */
    public function water($category, $path, $file)
    {
        $config = $this->getConfig($category);
        $text = $config['text'];
        $textLent = mb_strlen($text);
        $color = [255, 255, 255];
        $font = public_path() . '/uploads/images/create/msyh.ttc';
        $font = public_path() . '/uploads/images/create/FZCSJW.TTF';
        foreach ($thumb_arr as $val) {
            if ($category == 'create') {
                $file = public_path() . $val['filename'];
                $img = Image::openFromFile($file);
                $im = $img->handle;
                $w = imagesx($im);
                $h = imagesy($im);
                //$fontSize = intval($w*0.6/$textLent);
                $fontSize = intval($w * 0.8 / $textLent / 1.4);
                //$c = imagecolorallocate($im, $color[0], $color[1], $color[2]);
                $c = imagecolorallocatealpha($im, $color[0], $color[1], $color[2], 65);

                $rr = imagettfbbox($fontSize, 0, $font, $text);
                $dstX = max(1, $w / 2 - ($rr[4] - $rr[0]) / 2);
                $dstY = max(1, $h / 2 - ($rr[5] - $rr[1]) / 2);
                imagefttext($im, $fontSize, 0, $dstX, $dstY, $c, $font, mb_convert_encoding($text, 'html-entities', 'utf-8'));
                $img->save($file);
            }
        }
    }

    /**
     * 上传mp3文件
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function uploadMp3(Request $request)
    {
        $category = $request->get($this->categoryName);
        $config = $this->getConfig($category);

        if ($request->files->count() != 1) {
            throw new \Exception('upload file is empty');
        }
        $files = $request->allFiles();
        $uploadFile = reset($files);

        $randomName = $uploadFile->hashName();
        $randomName = explode('.', $randomName)[0] . '.mp3';
        $path = $uploadFile->storeAs($config['path'], $randomName, $this->fileSystemDriver);
        $index = strrpos($path, '/') + 1;

        $filename = substr($path, $index);
        $path = $this->uploadPath() . substr($path, 0, $index);

        return $this->outputFormat($path,$filename,$config['type']);
    }

    public function outputFormat($names, $type)
    {
        return [
            'files' => $names,
            'type' => $type
        ];
    }
}