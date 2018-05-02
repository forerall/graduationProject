<?php
namespace App\Tools\Auto\QQ;

use App\Tools\Vendor\Curl;

/**
 * Created by PhpStorm.
 * User: ygy
 * Date: 2017/5/20
 * Time: 22:20
 */
class QQHelp
{
    protected $qq;
    protected $curl;
    protected $hash;
    protected $vfwebqq;
    protected $cookie;
    protected $clientid;
    protected $psessionid;

    public function __construct($qq, $cookie, $vfwebqq, $hash)
    {
        $this->curl = Curl::instance();
        $this->qq = $qq;
        $this->hash = $hash;
        $this->cookie = $cookie;
        $this->vfwebqq = $vfwebqq;
        $this->clientid = '53999199';
    }



    public function getFriendList()
    {
        $this->checkLogin();
        $param = [
            'vfwebqq' => $this->vfwebqq,
            'hash' => $this->hash,
        ];
        $url = "http://s.web2.qq.com/api/get_user_friends2";
        $res = $this->setQueryHeader()->post($url, $this->parseParam($param));
        return $this->parseOutput($res);
    }

    public function getQunList()
    {
        $this->checkLogin();
        $param = [
            'vfwebqq' => $this->vfwebqq,
            'hash' => $this->hash,
        ];
        $url = "http://s.web2.qq.com/api/get_group_name_list_mask2";
        $res = $this->setQueryHeader()->post($url, $this->parseParam($param));
        return $this->parseOutput($res);
    }

    public function sendQunMessage($group_uin, $msg)
    {
        $this->checkLogin();
        $param = '{"group_uin":' . $group_uin . ',"content":"[\"' . $msg . '\",[\"font\",{\"name\":\"宋体\",\"size\":10,\"style\":[0,0,0],\"color\":\"000000\"}]]","face":0,"clientid":' . $this->clientid . ',"msg_id":29240001,"psessionid":"' . $this->psessionid . '"}';
        $param = 'r=' . urlencode($param);
        $url = "https://d1.web2.qq.com/channel/send_qun_msg2";
        $res = $this->setSendHeader()->post($url, $param);
        return $this->parseOutput($res);
    }

    public function sendFriendMessage($to, $msg)
    {
        $this->checkLogin();
        $param = '{"to":' . $to . ',"content":"[\"' . $msg . '\",[\"font\",{\"name\":\"宋体\",\"size\":10,\"style\":[0,0,0],\"color\":\"000000\"}]]","face":0,"clientid":' . $this->clientid . ',"msg_id":29240002,"psessionid":"' . $this->psessionid . '"}';
        $param = 'r=' . urlencode($param);
        $url = "https://d1.web2.qq.com/channel/send_buddy_msg2";
        $res = $this->setSendHeader()->post($url, $param);
        return $this->parseOutput($res);
    }














    protected function checkLogin()
    {
        if (empty($this->psessionid)) {
            preg_match('/ptwebqq=([^ ;]*)/', $this->cookie, $matches);
            $ptwebqq = $matches[1];
            $url = "http://d1.web2.qq.com/channel/login2";
            $param = '{"ptwebqq":"' . $ptwebqq . '","clientid":'.$this->clientid.',"psessionid":"","status":"online"}';
            $param = 'r=' . urlencode($param);
            $res = $this->setLoginHeader()->post($url, $param);
            $res = $this->parseOutput($res);
            if ($res['retcode'] == 0) {
                //$this->vfwebqq = $res['result']['vfwebqq'];
                $this->psessionid = $res['result']['psessionid'];
            } else {
                throw new \Exception('登录失败');
            }
        }
        return $this->curl;

    }

    protected function setLoginHeader()
    {
        $header = [];
        $header[] = "Referer: http://d1.web2.qq.com/proxy.html?v=20151105001&callback=1&id=2";
        $header[] = 'Connection: Keep-Alive ';
        $header[] = 'Cookie: ' . $this->cookie;
        $this->curl->setHeader($header);
        return $this->curl;
    }

    protected function setQueryHeader()
    {
        $header = [];
        $header[] = "Referer: http://s.web2.qq.com/proxy.html?v=20130916001&callback=1&id=1";
        $header[] = 'Connection: Keep-Alive ';
        $header[] = 'Cookie: ' . $this->cookie;
        $this->curl->setHeader($header);
        return $this->curl;
    }

    protected function setSendHeader()
    {
        $header = [];
        $header[] = "Referer: https://d1.web2.qq.com/cfproxy.html?v=20151105001&callback=1";
        $header[] = 'Connection: Keep-Alive ';
        $header[] = 'Cookie: ' . $this->cookie;
        $this->curl->setHeader($header);
        return $this->curl;
    }

    protected function parseParam($param)
    {
        return 'r=' . urlencode(json_encode($param, JSON_UNESCAPED_UNICODE));
    }

    protected function parseOutput($data)
    {
        return json_decode($data, true);
    }
}