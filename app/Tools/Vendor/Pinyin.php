<?php

namespace App\Tools\Vendor;
/**
 * Created by PhpStorm.
 * User: ygy
 * Date: 2017/5/22
 * Time: 15:20
 */


class Pinyin
{
    protected $url = "https://zhongwenzhuanpinyin.51240.com/web_system/51240_com_www/system/file/zhongwenzhuanpinyin/data/?ajaxtimestamp=1495437543765";

    protected function parseData($data)
    {
        return strval($data);
    }

    protected function renderBody($body)
    {
        return http_build_query($body);
    }


    /**
     * 汉字转拼音
     * @param $data
     * @return string
     */
    public function Action($data)
    {
        $data = $this->parseData($data);
        $params = [
            'zwzyp_zhongwen' => $data,
            'zwzyp_shengdiao' => '0',
            'zwzyp_wenzi' => '0',
            'zwzyp_jiange' => '0',
            'zwzyp_duozhongduyin' => '0'
        ];
        $params = $this->renderBody($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, []);
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    // 要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_HEADER, 0); // 不要http header 加快效率
        //curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);    // post 提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $output = curl_exec($ch);
        curl_close($ch);
        return strip_tags($output);
    }
}