<?php

namespace App\Tools\Wechat\Event;

/*
 * $config["token"] 消息接口token
 *
 * 消息推送处理
 */
use App\Tools\Wechat\Event\Crypt\WXBizMsgCrypt;
use App\Tools\Wechat\Event\Msg\SubscribeMsg;
use App\Tools\Wechat\Event\Msg\TextMsg;
use App\Tools\Wechat\WxConfig;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WxMsgCallback
{
    protected $signature;
    protected $timestamp;
    protected $nonce;
    protected $echostr;
    protected $encrypt_type;
    protected $msg_signature;

    protected $request;
    /**
     * 消息回复回调
     * @var array
     */
    protected $msgCallback = [
        'text' => null,//文本消息
        'event' => null,//关注事件
    ];

    public function __construct()
    {
        $this->signature = isset($_GET['signature'])?$_GET['signature']:'';
        $this->timestamp = isset($_GET['timestamp'])?$_GET['timestamp']:'';
        $this->nonce = isset($_GET['nonce'])?$_GET['nonce']:'';
        $this->echostr = isset($_GET['echostr'])?$_GET['echostr']:'';
        $this->encrypt_type = isset($_GET['encrypt_type'])?$_GET['encrypt_type']:'';
        $this->msg_signature = isset($_GET['msg_signature'])?$_GET['msg_signature']:'';
        //消息合法性验证
        if (!$this->checkSign()) {
            $this->fail('验证失败');
        }
        $this->request = $this->buildRequest();
    }

    /**
     * 文本消息处理
     * @param $callback
     * @return $this
     */
    public function onTextMessage($callback)
    {
        if ($callback instanceof \Closure) {
            $this->msgCallback['text'] = $callback;
        }
        return $this;
    }

    /**
     * 关注事件处理
     * @param $callback
     * @return $this
     */
    public function onSubscribeMessage($callback)
    {
        if ($callback instanceof \Closure) {
            $this->msgCallback['event'] = $callback;
        }
        return $this;
    }

    /**
     * 启动消息处理
     */
    public function start()
    {
        $response = "";
        $type = $this->request['MsgType'];
        //解析消息实体
        $msg = $this->parseRequest($type);
        //处理消息
        if (isset($this->msgCallback[$type]) && $this->msgCallback[$type]) {
            $callback = $this->msgCallback[$type];
            $response = $callback($this, $msg);
        }
        if ($this->encrypt_type=='aes') {
            $pc = new WXBizMsgCrypt(WxConfig::instance()->messageToken, WxConfig::instance()->encodingAESKey, WxConfig::instance()->appId);
            $errCode = $pc->encryptMsg($response, $_GET["timestamp"], $_GET["nonce"], $encryptMsg);
            if ($errCode == 0) {
                $response = $encryptMsg;
            } else {
                $this->fail("encrypt msg fail:" . $errCode);
            }
        }
        return $response;
    }

    /**
     * 回复文本
     * @param $content
     * @return string
     */
    public function replyText($content)
    {
        $xml = "<xml>
        <ToUserName><![CDATA[{$this->request['FromUserName']}]]></ToUserName>
        <FromUserName><![CDATA[{$this->request['ToUserName']}]]></FromUserName>
        <CreateTime>{time()}</CreateTime>
        <MsgType><![CDATA[text]]></MsgType>
        <Content><![CDATA[{$content}]]></Content>
        </xml>";
        return $xml;
    }


    //消息验证
    private function checkSign()
    {
        return WxSafe::checkSignature([$this->timestamp, $this->nonce], $this->signature);
    }

    //生成请求结构
    private function buildRequest()
    {
        $xml = file_get_contents("php://input");
        //DB::table('test')->insert(['name'=>$xml]);
        //配置时微信调用，返回$_GET['echostr']
        if (empty($xml)) {
            exit($this->echostr);
        }
        if ($this->encrypt_type=='aes') {
            $pc = new WXBizMsgCrypt(WxConfig::instance()->messageToken, WxConfig::instance()->encodingAESKey, WxConfig::instance()->appId);
            $errCode = $pc->decryptMsg($this->msg_signature, $this->timestamp, $this->nonce, $xml, $msg);
            if ($errCode == 0) {
                $xml = $msg;
            } else {
                $this->fail("decrypt msg fail:" . $errCode);
            }
        }
        $xml = new \SimpleXMLElement($xml);
        if (!$xml) {
            $this->fail("SimpleXMLElement Fail");
        }
        $data = array();
        foreach ($xml as $key => $value) {
            $data[$key] = strval($value);
        }
        return $data;
    }

    //解析消息对象
    protected function parseRequest($type)
    {
        $msg = '';
        switch ($type) {
            case 'text':
                $msg = new TextMsg();
                break;
            case 'event':
                $msg = new SubscribeMsg();
                break;
            default:
                $this->fail('msg type实体类不存');
        }
        foreach ($this->request as $key => $val) {
            if (property_exists($msg, $key)) {
                $msg->$key = $val;
            }
        }
        return $msg;
    }

    private function fail($msg)
    {
        throw new \Exception($msg);
    }
}