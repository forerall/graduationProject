<?php
namespace App\Tools\Wechat;

use App\Tools\Output;
use App\Tools\Wechat\Weixin\WTool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/**
 * 多个网站共享授权登陆
 * Class WxShareLogin
 * @package App\Tools\Wechat
 */
class WxShareLogin
{
    protected static $baseRedirectUrl = 'http://www.myfusz.cn/wxLoginRedirect?third=1';
    protected static $userInfoUrl = 'http://www.myfusz.cn/getWxUserInfo?code=';
    protected static $appId = 'wx74ae28223db6919c';

    /**
     * 注册路由
     */
    public static function routes()
    {
        Route::any('/wxLoginRedirect', 'WxController@wxLoginRedirect');//code重定向
        Route::any('/getWxUserInfo', 'WxController@getWxUserInfo');//获取用户信息
    }

    /**
     * 服务端处理重定向
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public static function serverLoginRedirect(Request $request)
    {
        $_uri = $request->get('_uri');
        $code = $request->get('code');

        if (empty($_uri)) {
            return '_uri is empty!';
        }

        if (empty($code)) {
            return 'code is empty!';
        }

        if (strpos($_uri, '?') !== false) {
            $_uri .= '&code=' . $code;
        } else {
            $_uri .= '?code=' . $code;
        }
        return redirect($_uri);
    }

    /**
     * 服务端查询微信用户信息
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public static function serverGetUserInfo(Request $request)
    {
        $code = $request->get('code');
        if (empty($code)) {
            return Output::Fail('code is empty');
        }
        $wx = new WTool();
        $info = $wx->getUserInfoByCode($code);
        return Output::Success('请求成功', $info);
    }

    /**
     * 服务端appId
     * @return string
     */
    public static function serverAppId()
    {
        return self::$appId;
    }

    /**
     * 客户端登陆重定向地址
     * @param $url
     * @return string
     */
    public static function clientGetLoginRedirectUrl($url)
    {
        return self::$baseRedirectUrl . '&_uri=' . urlencode($url);
    }

    /**
     * 客户端获取微信用户信息
     * @param $code
     * @return mixed
     */
    public static function clientGetUserInfo($code)
    {
        $result = file_get_contents(self::$userInfoUrl . $code);
        return json_decode($result, true);
    }


}