<?php
/**
 * Created by PhpStorm.
 * User: arts-mgcx
 * Date: 2016/11/1
 * Time: 14:14
 */

namespace App\Tools\Image;

/*图像处理类*/
class Image {
    public static function string($dst_image,$dst_x,$dst_y,$dst_h,$font,$color,$text,$charset = 'utf-8'){
        $font_size = $dst_h;
        $dst_y +=$dst_h;
        $c = imagecolorallocate($dst_image->handle, $color[0], $color[1], $color[2]);
        return imagefttext($dst_image->handle, $font_size, 0, $dst_x, $dst_y, $c, $font, mb_convert_encoding($text,'html-entities',$charset));
    }
    //横图横切，纵图纵切
    public static function thumb($src_image,$dst_w,$dst_h){
        $is_w = $src_image->width>=$src_image->height;
        if($is_w){
            $dst_h = $dst_w*$src_image->height/$src_image->width;
        }else{
            $dst_w = $dst_h*$src_image->width/$src_image->height;
        }
        return self::crop($src_image,0,0,$dst_w,$dst_h,$src_image->width,$src_image->height);
    }
    //按宽度切
    public static function thumbByWidth($src_image, $dst_w, $dst_h)
    {
        $dst_h = $dst_w / $src_image->width * $src_image->height;
        return self::crop($src_image, 0, 0, $dst_w, $dst_h, $src_image->width, $src_image->height);
    }
    //将原图像一个区域复制到 另一个图像
    public static function crop($src_image,$src_x,$src_y,$dst_w,$dst_h,$src_w = 0,$src_h = 0){
        $dst_image = self::imageCreate($src_image,$dst_w,$dst_h);
        $src_rect = array(
            'x' =>  $src_x>=0?$src_x:0,
            'y' =>  $src_y>=0?$src_y:0,
            'width' =>  $src_w>0?$src_w:$dst_w,
            'height' =>  $src_h>0?$src_h:$dst_h
        );
        self::imageCopy($dst_image,$src_image,$dst_image->client_rect(),$src_rect);
        return $dst_image;
    }
    //创建图像对象
    public static function imageCreate($src_image,$dst_w,$dst_h){
        if($dst_w<=0||$dst_h<=0){
            return false;
        }
        //创建新图像
        $dst = imagecreatetruecolor($dst_w, $dst_h);
        // 调整默认颜色
        $color = imagecolorallocate($dst, 255, 255, 255);
        imagefill($dst, 0, 0, $color);
        return new Img($dst,$dst_w,$dst_h,$src_image->type,$src_image->mime);
    }
    //图像复制
    public static function imageCopy($dst_image,$src_image,$dst_rect,$src_rect){
        if($src_rect['x']<0||$src_rect['x']+$src_rect['width']>$src_image->width){
            throw new ImageProcessException('图像超出范围');
        }
        if($src_rect['y']<0||$src_rect['y']+$src_rect['height']>$src_image->height){
            throw new ImageProcessException('图像超出范围');
        }
        if($dst_rect['x']<0||$dst_rect['x']+$dst_rect['width']>$dst_image->width){
            throw new ImageProcessException('图像超出范围');
        }
        if($dst_rect['y']<0||$dst_rect['y']+$dst_rect['height']>$dst_image->height){
            throw new ImageProcessException('图像超出范围');
        }
        return imagecopyresampled($dst_image->handle,$src_image->handle,$dst_rect['x'],$dst_rect['y'],$src_rect['x'],$src_rect['y'],
            $dst_rect['width'],$dst_rect['height'],$src_rect['width'],$src_rect['height']);
    }

    public static function openFromIm($im,$type){
        if(!is_resource($im)){
            throw new ImageProcessException('不是资源对象');
        }
        return new Img($im,imagesx($im),imagesy($im),$type,'image/'.$type);
    }
    public static function openFromFile($filename){
        //检测图像文件
        if(!is_file($filename)) throw new ImageProcessException('不存在的图像文件:'.$filename);
        //获取图像信息
        $info = getimagesize($filename);
        //检测图像合法性
        if(false === $info || (IMAGETYPE_GIF === $info[2] && empty($info['bits']))){
            throw new ImageProcessException('非法图像文件');
        }
        //设置图像信息
        $info = array(
            'width'  => $info[0],
            'height' => $info[1],
            'type'   => image_type_to_extension($info[2], false),
            'mime'   => $info['mime'],
        );
        $fun = "imagecreatefrom{$info['type']}";
        $info['handle'] = $fun($filename);
        return new Img($info['handle'],$info['width'],$info['height'],$info['type'],$info['mime']);
    }

    //文字水印
    public static function watermark($src,$dst,$font){
//        $font = public_path().'\\uploads\\images\\create\\FZCSJW.TTF';
//        $src = public_path().'\\uploads\\images\\create\\3822951_135521683000_2.jpg';
//        $dst = public_path().'\\uploads\\images\\create\\111.jpeg';
        $text = "原作者版权所有";
        $fontSize = 10;
        $lineSpan = 100;
        $textSpan = 50;
        $textAngle = 30;
        $color = [255,255,255];
        $sin = sin(deg2rad($textAngle));
        $cos = cos(deg2rad($textAngle));
        $tan = tan(deg2rad($textAngle));


        $img = Image::openFromFile($src);
        $im = $img->handle;
        $imageWidth = imagesx($im);
        $imageHeight = imagesy($im);
        //$c = imagecolorallocate($im, $color[0], $color[1], $color[2]);
        $c = imagecolorallocatealpha($im, $color[0], $color[1], $color[2],65);

        //$fontSize = intval($w*0.8/$textLent/1.4);
        $rr = imagettfbbox($fontSize,0,$font,$text);
        $textWidth = abs($rr[4]-$rr[0]);
        $textHeight = abs($rr[5]-$rr[1]);

        $realHeight = $imageHeight + $imageWidth*$tan;//水印实际高度

        $textLength = $imageWidth/$cos + $sin*$imageHeight;//水印实际宽度
        $repeat = ceil($textLength/($textWidth+$textSpan));//文本重复次数
        for($i = $lineSpan;$i<$realHeight;$i+=($lineSpan/$cos)){
            $x = $sin*$cos*($i-$imageHeight);//每行起始x
            $offsetX = -1*($textWidth+$textSpan)*(1-$cos);//x轴倾斜偏移
            $offsetY = $tan*($textWidth+$textSpan+$offsetX);//y轴倾斜偏移
            $y = $i-$tan*$x;
            $t = $repeat;

            for(;$t>0;$t--){
                imagettftext($im, $fontSize, $textAngle, $x,$y , $c, $font, $text);
                $x+=($textWidth+$textSpan + $offsetX);
                $y-=$offsetY;
            }

        }
        $img->save($dst);
    }
}

/*图像信息类*/
class Img{
    public $width;
    public $height;
    public $type;
    public $mime;
    public $handle;

    public function __construct($handle,$width,$height,$type,$mime){
        $this->handle = $handle;
        $this->width = $width;
        $this->height = $height;
        $this->type = $type;
        $this->mime = $mime;
    }
    public function save($filename,$destroy = true){
        if(is_resource($this->handle)){
            $fun = 'image'.$this->type;
            $fun($this->handle, $filename);
            if($destroy){
                $this->destroy();
            }
            return true;
        }
        return false;
    }
    public function client_rect(){
        return array(
            'x' =>  0,
            'y' =>  0,
            'width' =>  $this->width,
            'height'    =>  $this->height
        );
    }
    public function destroy(){
        if(is_resource($this->handle)){
            imagedestroy($this->handle);
        }
    }
    public function __destruct(){
        $this->destroy();
    }


}

class ImageProcessException extends \Exception{

}