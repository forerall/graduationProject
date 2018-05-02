<?php
/**
 * Created by PhpStorm.
 * User: arts-mgcx
 * Date: 2016/11/25
 * Time: 13:57
 */

namespace App\Tools\Search\Lib;
use App\Tools\Search\Exception\DownLoadImageFailException;

class DownLoadImage
{
    public static function download($url,$filename){
        $client = new \GuzzleHttp\Client();
        try{
            $res = $client->request('GET',$url,[
                'stream' => true,
                //'allow_redirects' => false
            ]);
            if($res->getStatusCode()==200){
                $content = $res->getBody()->getContents();
                file_put_contents($filename,$content);
            }else{
                throw new DownLoadImageFailException($res->getStatusCode().':'.$url);
            }
        }catch(\Exception $e){
            file_put_contents($filename,$e->getMessage());
        }

    }
}