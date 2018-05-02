<?php
namespace App\Tools\Push;
//采用"PHP SDK 快速入门"， "第二步 获取访问凭证 "中获得的应用配置

//AppID：
//hNuO87YoF08sEYuqn4B1I
//AppSecret：
//PTWIOPbbJFAFPGYAfi6zL6
//AppKey：
//BrRYJfST8J7TQOSrg7K2C3
//MasterSecret：
//dWG2KR0cRJ74Z9hx5grEn7

//define('APPKEY', 'BrRYJfST8J7TQOSrg7K2C3');
//define('APPID', 'hNuO87YoF08sEYuqn4B1I');
//define('MASTERSECRET', 'dWG2KR0cRJ74Z9hx5grEn7');
define('APPKEY', 'XBLtuPr1Sa7sYiGtRzSVw1');
define('APPID', 'xvzFMBSDRm7YZ3yFq1SEq9');
define('MASTERSECRET', '8zpQAxnZmM76zsyC6mPYe2');
define('HOST', 'http://sdk.open.api.igexin.com/apiex.htm');
require_once(dirname(__FILE__) . '/' . 'IGt.Push.php');
require_once(dirname(__FILE__) . '/' . 'igetui/IGt.AppMessage.php');
require_once(dirname(__FILE__) . '/' . 'igetui/IGt.APNPayload.php');
require_once(dirname(__FILE__) . '/' . 'igetui/template/IGt.BaseTemplate.php');
require_once(dirname(__FILE__) . '/' . 'IGt.Batch.php');
require_once(dirname(__FILE__) . '/' . 'igetui/utils/AppConditions.php');

class Push
{

    public function pushToSingle($user, $template){
        $igt = new \IGeTui(HOST, APPKEY, MASTERSECRET);
        //定义"SingleMessage"
        $message = new \IGtSingleMessage();
        $message->set_isOffline(true);//是否离线
        $message->set_offlineExpireTime(3600 * 12 * 1000);//离线时间
        $message->set_data($template);//设置推送消息类型
        $message->set_PushNetWorkType(0);//设置是否根据WIFI推送消息，2为4G/3G/2G，1为wifi推送，0为不限制推送
        //接收方
        $target = new \IGtTarget();
        $target->set_appId(APPID);
        $target->set_clientId($user->clientid);

        try {
            $rep = $igt->pushMessageToSingle($message, $target);

        } catch (\RequestException $e) {
            $requstId = $e->getRequestId();
            //失败时重发
            $rep = $igt->pushMessageToSingle($message, $target, $requstId);
        }
        if (array_key_exists('result', $rep)) {
            return $rep['result'] == 'ok';
        }
        return false;
    }
    //单推接口案例
    public function pushMessageToSingle($user, $title, $content,$badge)
    {
        if($user->ios){
            $template = $this->IGtTransmissionTemplateDemo($title, $content,$badge);
            $result = $this->pushToSingle($user,$template);
        }else{
            $template = $this->IGtNotificationTemplate($title, $content);
            $result = $this->pushToSingle($user,$template);
        }
    }

    protected function IGtTransmissionTemplateDemo($title, $content,$badge = 0)
    {
        $template = new \IGtTransmissionTemplate();
        $template->set_appId(APPID);//应用appid
        $template->set_appkey(APPKEY);//应用appkey
        $template->set_transmissionType(2);//透传消息类型
        $template->set_transmissionContent($content);//透传内容
        //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息
        //APN简单推送
//        $template = new IGtAPNTemplate();
//        $apn = new IGtAPNPayload();
//        $alertmsg=new SimpleAlertMsg();
//        $alertmsg->alertMsg="";
//        $apn->alertMsg=$alertmsg;
////        $apn->badge=2;
////        $apn->sound="";
//        $apn->add_customMsg("payload","payload");
//        $apn->contentAvailable=1;
//        $apn->category="ACTIONABLE";
//        $template->set_apnInfo($apn);
//        $message = new IGtSingleMessage();

        //APN高级推送
        $apn = new \IGtAPNPayload();
        $alertmsg = new \DictionaryAlertMsg();
        $alertmsg->body = $content;
        $alertmsg->actionLocKey = "ActionLockey";
        $alertmsg->locKey = " ";
        $alertmsg->locArgs = array("locargs");
        $alertmsg->launchImage = "launchimage";
//        IOS8.2 支持
        $alertmsg->title = $title;
        $alertmsg->titleLocKey = "TitleLocKey";
        $alertmsg->titleLocArgs = array("TitleLocArg");

        $apn->alertMsg = $alertmsg;
        $apn->badge = $badge;
        $apn->sound = "";
        $apn->add_customMsg("payload", "payload");
        $apn->contentAvailable = 1;
        $apn->category = "ACTIONABLE";
        $template->set_apnInfo($apn);

        return $template;
    }

    protected function IGtNotificationTemplate($title, $content)
    {
        $template = new \IGtNotificationTemplate();
        $template->set_appId(APPID);//应用appid
        $template->set_appkey(APPKEY);//应用appkey
        $template->set_transmissionType(1);//透传消息类型
        $template->set_transmissionContent("test");//透传内容
        $template->set_title($title);//通知栏标题
        $template->set_text($content);//通知栏内容
        $template->set_logo("http://wwww.igetui.com/logo.png");//通知栏logo
        $template->set_isRing(true);//是否响铃
        $template->set_isVibrate(true);//是否震动
        $template->set_isClearable(true);//通知栏是否可清除
        return $template;
    }


}