<?php
/**
 * Created by PhpStorm.
 * User: arts-mgcx
 * Date: 2016/12/27
 * Time: 13:45
 */

namespace App\Tools\Captcha;

class Captcha
{
    private $fontSize;
    private $_image;
    private $_color   = NULL;     // 验证码字体颜色
    private $fontttf   = NULL;
    private $codeSet   =  '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY';
    private $width;
    private $height;
    private $code;

    /**
     * 获取验证码图片
     * @param $height
     * @param int $len
     * @return string
     */
    public function make($height,$len = 4) {
        $this->height = $height;
        $this->fontSize = $this->height*700/1000;
        $this->width = $len*$this->fontSize*1.2;
        //echo $this->fontSize;exit;
        // 建立一幅 $this->imageW x $this->imageH 的图像
        $this->_image = imagecreate( $this->width, $this->height);
        // 设置背景
        imagecolorallocate($this->_image, rand(0,255), rand(0,255),rand(0,255));

        // 验证码字体随机颜色
        $this->_color = imagecolorallocate($this->_image, mt_rand(1,150), mt_rand(1,150), mt_rand(1,150));
        // 验证码使用随机字体
        $this->fontttf = dirname(__FILE__).'/ttfs/'.rand(1,6).'.ttf';

        // 绘杂点
        //$this->_writeNoise();
        $this->createBg();
        // 绘干扰线
        $this->_writeCurve();

        // 绘验证码
        $code = array(); // 验证码
        $codeNX =  mt_rand($this->fontSize*0.2, $this->fontSize*0.8); // 验证码第N个字符的左边距
        $codeNY = $this->height - ($this->height - $this->fontSize)/2;
        for ($i = 0; $i<$len; $i++) {
            $code[$i] = $this->codeSet[mt_rand(0, strlen($this->codeSet)-1)];
            //$codeNY = mt_rand($codeNY*0.7, $codeNY*1.4);
            imagettftext($this->_image, $this->fontSize, mt_rand(-40, 40), $codeNX, $codeNY, $this->_color, $this->fontttf, $code[$i]);
            $codeNX  += mt_rand($this->fontSize*0.8, $this->fontSize*1.2);

        }
        $this->code = implode('',$code);
        return $this->code;

    }
    //生成背景
    private function createBg() {
        $color = imagecolorallocate($this->_image, mt_rand(157,255), mt_rand(157,255), mt_rand(157,255));
        imagefilledrectangle($this->_image,0,$this->height,$this->width,0,$color);
    }

    /**
     * 输出验证码图像
     */
    public function output(){
        header('Cache-Control: private, max-age=0, no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        header("content-type: image/png");

        // 输出图像
        imagepng($this->_image);
        imagedestroy($this->_image);
    }

    /**
     * 画一条由两条连在一起构成的随机正弦函数曲线作干扰线(你可以改成更帅的曲线函数)
     *
     *      高中的数学公式咋都忘了涅，写出来
     *		正弦型函数解析式：y=Asin(ωx+φ)+b
     *      各常数值对函数图像的影响：
     *        A：决定峰值（即纵向拉伸压缩的倍数）
     *        b：表示波形在Y轴的位置关系或纵向移动距离（上加下减）
     *        φ：决定波形与X轴位置关系或横向移动距离（左加右减）
     *        ω：决定周期（最小正周期T=2π/∣ω∣）
     *
     */
    private function _writeCurve() {
        $px = $py = 0;

        // 曲线前部分
        $A = mt_rand(1, $this->height/2);                  // 振幅
        $b = mt_rand(-$this->height/4, $this->height/4);   // Y轴方向偏移量
        $f = mt_rand(-$this->height/4, $this->height/4);   // X轴方向偏移量
        $T = mt_rand($this->height, $this->width*2);  // 周期
        $w = (2* M_PI)/$T;

        $px1 = 0;  // 曲线横坐标起始位置
        $px2 = mt_rand($this->width/2, $this->width * 0.8);  // 曲线横坐标结束位置

        for ($px=$px1; $px<=$px2; $px = $px + 1) {
            if ($w!=0) {
                $py = $A * sin($w*$px + $f)+ $b + $this->height/2;  // y = Asin(ωx+φ) + b
                $i = (int) ($this->fontSize/5);
                while ($i > 0) {
                    imagesetpixel($this->_image, $px + $i , $py + $i, $this->_color);  // 这里(while)循环画像素点比imagettftext和imagestring用字体大小一次画出（不用这while循环）性能要好很多
                    $i--;
                }
            }
        }

        // 曲线后部分
        $A = mt_rand(1, $this->height/2);                  // 振幅
        $f = mt_rand(-$this->height/4, $this->height/4);   // X轴方向偏移量
        $T = mt_rand($this->height, $this->width*2);  // 周期
        $w = (2* M_PI)/$T;
        $b = $py - $A * sin($w*$px + $f) - $this->height/2;
        $px1 = $px2;
        $px2 = $this->width;

        for ($px=$px1; $px<=$px2; $px=$px+ 1) {
            if ($w!=0) {
                $py = $A * sin($w*$px + $f)+ $b + $this->height/2;  // y = Asin(ωx+φ) + b
                $i = (int) ($this->fontSize/5);
                while ($i > 0) {
                    imagesetpixel($this->_image, $px + $i, $py + $i, $this->_color);
                    $i--;
                }
            }
        }
    }

    /**
     * 画杂点
     * 往图片上写不同颜色的字母或数字
     */
    private function _writeNoise() {
        $codeSet = '2345678abcdefhijkmnpqrstuvwxyz';
        for($i = 0; $i < 10; $i++){
            //杂点颜色
            $noiseColor = imagecolorallocate($this->_image, mt_rand(150,225), mt_rand(150,225), mt_rand(150,225));
            for($j = 0; $j < 5; $j++) {
                // 绘杂点
                imagestring($this->_image, 5, mt_rand(-10, $this->width),  mt_rand(-10, $this->height), $codeSet[mt_rand(0, 29)], $noiseColor);
            }
        }
    }



}