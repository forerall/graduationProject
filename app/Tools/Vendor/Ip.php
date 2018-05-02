<?php
/**
 * Created by PhpStorm.
 * User: ygy
 * Date: 2017/5/23
 * Time: 9:38
 */

namespace App\Tools\Vendor;


class Ip
{
    //http://ip.taobao.com/service/getIpInfo.php?ip=106.122.183.202

    protected $url = "http://ip.taobao.com/service/getIpInfo.php?";

    protected function parseData($data)
    {
        return strval($data);
    }

    protected function renderBody($body)
    {
        return http_build_query($body);
    }


    /**
     * Ip地址查询
     * @param $data
     * @return string
     */
    public function Action($data)
    {
        $data = $this->parseData($data);
        $params = [
            'ip' => $data,
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
        return json_decode($output,true);
    }
}