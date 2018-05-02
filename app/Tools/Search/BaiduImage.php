<?php
/**
 * Created by PhpStorm.
 * User: arts-mgcx
 * Date: 2016/11/25
 * Time: 15:30
 */

namespace App\Tools\Search;


class BaiduImage
{
    protected $keyword;
    protected $api = "http://image.baidu.com/search/acjson";

    public function __construct($keyword)
    {
        $this->keyword = $keyword;
    }

    protected function decodeObjUrl($objUrl = ''){
        if(empty($objUrl)) return '';
        $map = array(
            'w' => "a", 'k'=> "b", 'v'=> "c", '1'=> "d",
            'j'=> "e", 'u'=> "f", '2'=> "g", 'i'=> "h",
            't'=> "i", '3'=> "j", 'h'=> "k", 's'=> "l",
            '4'=> "m", 'g'=> "n", '5'=> "o", 'r'=> "p",
            'q'=> "q", '6'=> "r", 'f'=> "s", 'p'=> "t",
            '7'=> "u", 'e'=> "v", 'o'=> "w", '8'=> "1",
            'd'=> "2", 'n'=> "3", '9'=> "4", 'c'=> "5",
            'm'=> "6", '0'=> "7", 'b'=> "8", 'l'=> "9",
            'a'=> "0",
            '_z2C$q'=> ":",
            '_z&e3B'=> ".",
            'AzdH3F'=> "/"
        );

        $objUrl = str_replace('_z2C$q',$map['_z2C$q'],$objUrl);
        $objUrl = str_replace('_z&e3B',$map['_z&e3B'],$objUrl);
        $objUrl = str_replace('AzdH3F',$map['AzdH3F'],$objUrl);

        for($i=0;$i<strlen($objUrl);$i++){
            $temp = isset($map[$objUrl[$i]])?$map[$objUrl[$i]]:'';
            if($temp!=''){
                $objUrl[$i] = $temp;
            }
        }
        return $objUrl;
    }

    protected function url($page){
        $rn = 30;
        $pn = $page*$rn;
        $word = urlencode($this->keyword);
        $param = "tn=resultjson_com&ipn=rj&ct=201326592&is=&fp=result&queryWord={$word}&cl=2&lm=-1&ie=utf-8&oe=utf-8&adpicid=&st=&z=&ic=&word={$word}&s=&se=&tab=&width=&height=&face=&istype=&qc=&nc=&fr=&gsm=b4&1478230429415=";
        return $this->api."?pn={$pn}&rn={$rn}&".$param;
    }

    protected function dataProcess($content){
        $image_array = [];
        $data = json_decode($content);
        $data = $data->data;
        if($data){
            foreach($data as $image){
                if(!isset($image->objURL))continue;
                $image_array[] = [
                    'url'=>$this->decodeObjUrl($image->objURL),
                    'url2'=>isset($image->replaceUrl)?$image->replaceUrl[0]->ObjURL:'',
                    'type'=>$image->type

                ];
            }
        }
        return $image_array;
    }

    public function handle($page){
        $client = new \GuzzleHttp\Client(['base_uri' => 'http://image.baidu.com']);
        $res = $client->request('GET',$this->url($page),[
            'stream' => true
        ]);
        if($res->getStatusCode()==200){
            $content = $res->getBody()->getContents();
        }else{
            throw new \Exception('http status:'.$res->getStatusCode().' on '.$this->url());
        }
        return $this->dataProcess($content);
    }

}