<?php

namespace App\Http\Middleware;

use App\Tools\Wechat\Weixin\WTool;
use App\User;
use Closure;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class WechatLogin
{

    public function __construct()
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $recommend_user_id = $request->get('uid', 0);
        $recommend_user_id1 = 0;
        $recommend_user_id2 = 0;
        if ($recommend_user_id) {
            $rr = User::where('proxy', '>', 0)->find($recommend_user_id);
            if ($rr) {
                $recommend_user_id2 = $rr->recommend_user_id;
                if ($recommend_user_id2) {
                    $rr = User::where('proxy', '>', 0)->find($recommend_user_id2);
                    if ($rr) {
                        $recommend_user_id1 = $rr->recommend_user_id;
                    }
                }
            } else {
                $recommend_user_id = 0;
            }
        }

        if (!Auth::guard($guard)->check()) {
            //微信端,微信端app模式，不是测试模式下
            if (strpos(Input::server('HTTP_USER_AGENT'), 'MicroMessenger') !== false || (config('app.appmode') == 'wechat' && !config('app.testenv'))) {
                $wx = new WTool();
                $user = $wx->getUserInfo(2);
                if ($user && isset($user['openid'])) {
                    $u = User::where('openid', $user['openid'])->first();

                    if (is_null($u)) {
                        $u = User::create([
                            'nickname' => $user['nickname'],
                            'avatar' => $user['headimgurl'],
                            'openid' => $user['openid'],
                            'source' => 4,//微信
                            //'phone' => '',
                            //'email' => '',
                            'name' => $user['nickname'],
                            'password' => '',
                            'recommend_user_id' => $recommend_user_id,
                            'recommend_user_id1' => $recommend_user_id1,
                            'recommend_user_id2' => $recommend_user_id2,
                        ]);
                    }

                    if ($u && $u->id) {

                        Auth::guard($guard)->loginUsingId($u->id);
                    }
                }
            } else {
                if (config('app.testenv')) {
                    if ($request->get('uid')) {
                        $u = User::findOrFail($request->get('uid'));
                    } else {
                        $u = User::first();
                    }
                    Auth::guard($guard)->loginUsingId($u->id);
                } else {
                    return "登录失败";
                }
            }
        }
        if (Auth::user()->state == 1) {
            exit('已禁用！');
        }
        $u = Auth::user();
        if (empty($u->recommend_user_id) && $recommend_user_id) {
            $u->recommend_user_id = $recommend_user_id;
        }
        if (empty($u->recommend_user_id1) && $recommend_user_id1) {
            $u->recommend_user_id1 = $recommend_user_id1;
        }
        if (empty($u->recommend_user_id2) && $recommend_user_id2) {
            $u->recommend_user_id2 = $recommend_user_id2;
        }
        $u->save();
        return $next($request);
    }
}
