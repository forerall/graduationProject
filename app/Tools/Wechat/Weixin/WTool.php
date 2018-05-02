<?php
/**
 * Created by PhpStorm.
 * User: arts-mgcx
 * Date: 2016/12/26
 * Time: 17:05
 */

namespace App\Tools\Wechat\Weixin;


use App\Tools\Wechat\Weixin\lib\Menu;
use App\Tools\Wechat\WxShareLogin;

class WTool extends WBase
{
    //获取js接口配置
    public function get_wx_config($api_list, $debug = false)
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $time = time();
        $noncestr = $this->getRandChar(16);
        $param = array();
        $param['jsapi_ticket'] = $this->getJsToken();
        $param['noncestr'] = $noncestr;
        $param['timestamp'] = $time;
        $param['url'] = $url;
        $str = "jsapi_ticket=" . $param['jsapi_ticket'] . '&noncestr=' . $param['noncestr'] . '&timestamp=' . $param['timestamp'] . '&url=' . $param['url'];
        $str = sha1($str);

        $ret = array();
        $ret['noncestr'] = $noncestr;
        $ret['timestamp'] = $time;
        $ret['signature'] = $str;
        $ret['appid'] = $this->appId;

        foreach ($api_list as $key => $val) {
            $api_list[$key] = "'" . $val . "'";
        }
        $api_list = implode(',', $api_list);
        $debug = $debug ? 'true' : 'false';
        $config =
            "wx.config({"
            . "debug: {$debug}," // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            . "appId: '{$ret['appid']}'," // 必填，公众号的唯一标识
            . "timestamp: {$ret['timestamp']}," // 必填，生成签名的时间戳
            . "nonceStr: '{$ret['noncestr']}'," // 必填，生成签名的随机串
            . "signature: '{$ret['signature']}',"// 必填，签名，见附录1
            . "jsApiList: [{$api_list}]" // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
            . "});";
        if ($debug) {
            //$config .= "if('{$url}'!=location.href){alert('url错误：'+location.href);}";
        }

        return $config;
    }

    //获取用户信息
    public function getUserInfo($scope = 1, $state = 'state', $refer = '')
    {
        $useThirdWxLogin = config('app.useThirdWxLogin', false);
        if (isset($_GET['code']) && $_GET['code']) {
            if ($useThirdWxLogin) {
                $result = WxShareLogin::clientGetUserInfo($_GET['code']);
                if ($result['errCode'] == 0) {
                    return $result['data'];
                }
                throw new \Exception('第三方授权登陆失败,' . $result['errMsg']);
            }
            return $this->getUserInfoByCode($_GET['code']);
        } else {
            $scope_arr = array(
                1 => 'snsapi_base',//静默授权
                2 => 'snsapi_userinfo'
            );
            $appId = $this->appId;
            $redirect_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            if ($useThirdWxLogin) {
                $appId = WxShareLogin::serverAppId();
                $redirect_url = WxShareLogin::clientGetLoginRedirectUrl($redirect_url);
            }
            //添加跳转地址
            if ($refer) {
                if (strpos($redirect_url, '?') === false) {
                    $redirect_url .= '?ref=' . $refer;
                } else {
                    $redirect_url .= '&ref=' . $refer;
                }
            }
            $redirect_url = urlencode($redirect_url);
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?connect_redirect=1&appid={$appId}&redirect_uri={$redirect_url}&response_type=code&scope={$scope_arr[$scope]}&state={$state}#wechat_redirect";
            header('location:' . $url);
            exit;
        }
    }

    //使用code查询用户信息
    public function getUserInfoByCode($code)
    {
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->appId}&secret={$this->appSecret}&code={$code}&grant_type=authorization_code";
        $arr = $this->curlGet($url);
//            {
//                "access_token":"ACCESS_TOKEN",
//               "expires_in":7200,
//               "refresh_token":"REFRESH_TOKEN",
//               "openid":"OPENID",
//               "scope":"SCOPE",
//               "unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL"
//            }
        if (isset($arr['errcode']) && $arr['errcode']) {
            throw new \Exception($arr['errmsg']);
        }
        $openid = $arr['openid'];
        $access_token = $arr['access_token'];
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
        $user_info = $this->curlGet($url);
        if (isset($arr['errcode']) && $arr['errcode']) {
            throw new \Exception($arr['errmsg']);
        }
        //            {
//                "openid":" OPENID",
//               " nickname": NICKNAME,
//               "sex":"1",
//               "province":"PROVINCE"
//               "city":"CITY",
//               "country":"COUNTRY",
//                "headimgurl":    "http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/46",
//                "privilege":[
//                            "PRIVILEGE1"
//                "PRIVILEGE2"
//                ],
//                "unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL"
//            }
        return $user_info;
    }


    //分享
    public function shareConfig($url, $title, $desc, $imgUrl, $debug = false)
    {
        $config = $this->get_wx_config(['onMenuShareAppMessage', 'onMenuShareTimeline'], $debug);
        $config .= "wx.ready(function () {
            wx.onMenuShareTimeline({
                title: '{$title}', // 分享标题
                link: '{$url}', // 分享链接
                imgUrl: '{$imgUrl}', // 分享图标
                success: function () {
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                }
            });
            wx.onMenuShareAppMessage({
                title: '{$title}', // 分享标题
                desc: '{$desc}', // 分享描述
                link: '{$url}', // 分享链接
                imgUrl: '{$imgUrl}', // 分享图标
                type: 'link', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success: function () {
                    // 用户确认分享后执行的回调函数
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                }
            });
        });";
        return $config;
    }

    public function getLocation($callback = '')
    {
        return "wx.getLocation({
                type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
                success: function (res) {
                    var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                    var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                    var speed = res.speed; // 速度，以米/每秒计
                    var accuracy = res.accuracy; // 位置精度
                    if(typeof {$callback} == 'function'){
                        {$callback}(res);
                    }

                }
            });";
    }

    /**
     * 获取菜单对象数组
     * @return array
     */
    public function getMenuArray()
    {
        $menu = $this->getMenu();
        return Menu::decode($menu);
    }

    public function setMenu($menu)
    {
        $menu = Menu::menuObjectToArray($menu);//dd($menu);
        $json = json_encode($menu, JSON_UNESCAPED_UNICODE);
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$this->getToken()}";
        return $this->curlPost($url, $json);
    }
}