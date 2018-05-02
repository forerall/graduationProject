<?php
namespace App\Tools\Search;
/**
 * Created by PhpStorm.
 * User: arts-mgcx
 * Date: 2016/11/25
 * Time: 10:30
 */
class Test
{
    public static function images($keyword){
        function decodeObjUrl($objUrl = ''){
            if($objUrl == '') return '';
            //e={w:"a",k:"b",v:"c",1:"d",j:"e",u:"f",2:"g",i:"h",t:"i",3:"j",h:"k",s:"l",4:"m",g:"n",5:"o",r:"p",q:"q",6:"r",
            //f:"s",p:"t",7:"u",e:"v",o:"w",8:"1",d:"2",n:"3",9:"4",c:"5",m:"6",0:"7",b:"8",l:"9",a:"0",_z2C$q:":","_z&e3B":".",AzdH3F:"/"
           // },
            //t=/([a-w\d])/g,
            //n=/(_z2C\$q|_z&e3B|AzdH3F)/g;
            //return{compile:function(e){if(!e)return"";for(var t=(e.charCodeAt(0)+e.length).toString(16),n=1;n<e.length;n++)t+="g"+(e.charCodeAt(n)+e.charCodeAt(n-1)).toString(16);return t}
            //,uncompile:function(r){if(!r)return"";var o=r.replace(n,function(t,n){return e[n]});return o.replace(t,function(t,n){return e[n]})},
            //uncompileURL:function(e){return/^(http|https)/.test(e)?e:this.uncompile(e)}}}(),
            //r=function(){var e=["a","c","e","g","i","k","z","b","w","o"]
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
        $keyword = urlencode($keyword);
        $url = "http://image.baidu.com/search/acjson?tn=resultjson_com&ipn=rj&ct=201326592&is=&fp=result&queryWord={$keyword}&cl=2&lm=-1&ie=utf-8&oe=utf-8&adpicid=&st=&z=&ic=&word={$keyword}&s=&se=&tab=&width=&height=&face=&istype=&qc=&nc=&fr=&pn=210&rn=30&gsm=b4&1478230429415=";
        $jar = new \GuzzleHttp\Cookie\CookieJar;
        $client = new \GuzzleHttp\Client(['base_uri' => 'http://image.baidu.com']);

        $res = $client->request('GET',$url,[
            'stream' => true,
            'cookies' => $jar,
        ]);
        $content = $res->getBody()->getContents();
        $data = json_decode($content);
        $data = $data->data;
        dd($data);
        $image_array = [];
        $i=0;
        foreach($data as $image){
            if(!isset($image->objURL))continue;
            $image_array[] = decodeObjUrl($image->objURL);//$image->replaceUrl[0]->ObjURL;
            //echo '<img style="height: 100px" src="'.decodeObjUrl($image->objURL).'">';
            $img = decodeObjUrl($image->objURL);
            $i++;
            try{
                file_put_contents('baidu/'.$i.'.'.$image->type,file_get_contents($img));
            }catch(\Exception $e){
                if(isset($image->replaceUrl)){
                    //echo $image->replaceUrl[0]->ObjURL.'<br>';
                    try{
                        file_put_contents('baidu/'.$i.'.'.$image->type,file_get_contents($image->replaceUrl[0]->ObjURL));
                    }catch(\Exception $e){
                        echo $i.'=='.$image->replaceUrl[0]->ObjURL.'<br>';
                    }
                }else{
                    echo $i.'=='.$img.'<br>';
                }
            }
        }
        dd($image_array);exit;
        $dom = new Dom;
        $dom->load($content);
        $a = $dom->find('img');
        dd($a);
    }
}