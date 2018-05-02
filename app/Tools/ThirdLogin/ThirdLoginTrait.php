<?php
/**
 * Created by PhpStorm.
 * User: arts-mgcx
 * Date: 2017/3/27
 * Time: 14:31
 */

namespace App\Tools\ThirdLogin;


use App\Models\ThirdQq;
use App\Models\ThirdWeibo;
use App\Models\ThirdWx;
use App\Tools\Output;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

trait ThirdLoginTrait
{

    //第三方回调
    public function thirdCallback(Request $request){
        $code = $request->get('code');
        $type = $request->get('type');
        if($code){
            if($type=='wb'){
                $wb = new WeiboConnect();
                $uid = $wb->callBack($code);
                //已绑定自动登录，未绑定去绑定
                if($uid){
                    $user = User::where('weibo',$uid)->first();
                    if(!is_null($user)){
                        $login = $this->guard()->loginUsingId($user->id);
                        if($login){
                            return redirect($this->redirectTo);
                        }
                    }else{
                        $info = $wb->getUserInfo($uid);
                        $wb = ThirdWeibo::firstOrNew(['openid'=>$uid]);
                        if(!$wb->exists){
                            $wb->name = $info['name'];
                            $wb->headerImg = $info['avatar_large'];
                            $wb->location = $info['location'];
                        }
                        $wb->save();
                        session(['wb_id'=>$wb->id]);
                        return redirect("/bindInfo?type={$type}");
                    }
                }
            }else if($type=='qq'){
                $qq = new QQConnect();
                $uid = $qq->callBack($code);
                //已绑定自动登录，未绑定去绑定
                if($uid){
                    $user = User::where('qq',$uid)->first();
                    if(!is_null($user)){
                        $login = $this->guard()->loginUsingId($user->id);
                        if($login){
                            return redirect($this->redirectTo);
                        }
                    }else{
                        $info = $qq->getUserInfo($uid);
                        $qq = ThirdQq::firstOrNew(['openid'=>$uid]);
                        if(!$qq->exists){
                            $qq->name = $info['nickname'];
                            $qq->headerImg = $info['figureurl_2'];
                        }
                        $qq->save();
                        session(['qq_id'=>$qq->id]);
                        return redirect("/bindInfo?type={$type}");
                    }
                }
            }else if($type=='wx'){
                $wx = new WeixinConnect();
                $uid = $wx->callBack($code);
                if($uid){
                    $user = User::where('wechat',$uid)->first();
                    if(!is_null($user)){
                        $login = $this->guard()->loginUsingId($user->id);
                        if($login){
                            return redirect($this->redirectTo);
                        }
                    }else{
                        $info = $wx->getUserInfo($uid);
                        $wx = ThirdWx::firstOrNew(['openid'=>$uid]);
                        if(!$wx->exists){
                            $wx->name = $info['nickname'];
                            $wx->headerImg = $info['headimgurl'];
                        }
                        $wx->save();
                        session(['wx_id'=>$wx->id]);
                        return redirect("/bindInfo?type={$type}");
                    }
                }
            }
        }
        //错误返回到首页
        return redirect($this->redirectTo);
    }
    //绑定信息
    public function bindInfo(Request $request){
        if($request->isMethod('POST')){
            $this->validate($request,[
                'type'   =>  'required|in:wb,wx,qq',
                'phone' =>'required|phone',
                'code'  =>'required'
            ],[
                'type.in'   =>  '第三方登录类型错误',
                'phone.phone' =>'手机号不正确',
                'code.required'  =>'验证码不能为空'
            ]);
            $phone = $request->get('phone');
            $code = $request->get('code');
            $check = $this->smsService->checkCode($phone,$code,'bindThird');
            if($check['errCode']!=0){
                //return back()->withInput($request->all())->withErrors(['code'=>'验证码错误']);
                return Output::Fail('验证码错误');
            }

            $type = $request->get('type');//第三方类型
            if($type=='wb'){
                return $this->bindWeibo($request);
            }else if($type=='qq'){
                return $this->bindQQ($request);
            }else if($type=='wx'){
                return $this->bindWeixin($request);
            }

        }else{
            $type = $request->get('type');
            //$id = $request->get('id');
            return view('/auth/bindInfo')->with('type',$type);
        }
    }
    //跳转第三方
    public function jumpThird(Request $request){
        $type = $request->get('type');
        if($type=='wb'){
            $wb = new WeiboConnect();
            return redirect($wb->loginUrl());
        }else if($type == 'qq'){
            $qq = new QQConnect();
            return redirect($qq->loginUrl());
        }else if($type == 'wx'){
            $wx = new WeixinConnect();
            return redirect($wx->loginUrl());
        }
        echo '错误类型';
    }

    protected function bindWeibo($request){
        $id = session('wb_id',null);
        $wb_info = ThirdWeibo::find($id);
        if(is_null($wb_info)){
            return Output::Fail('绑定失败');
        }
        $user = User::firstOrNew(['phone'=>$request->get('phone')]);
        $user->weibo = $wb_info->openid;
        if(!$user->exists){
            $user->nickname = $wb_info->name;
            $user->avatar = $wb_info->headerImg;
            $user->source = 2;//微博
            $user->password = bcrypt($request->get('password'));
        }
        $user->save();
        if($user->id){
            $login = $this->guard()->loginUsingId($user->id);
            if($login){
                return Output::Success('绑定成功');
            }
        }
        return Output::Fail('绑定失败');
    }
    protected function bindQQ($request){
        $id = session('qq_id',null);
        $qq_info = ThirdQq::find($id);
        if(is_null($qq_info)){
            return Output::Fail('绑定失败');
        }
        $user = User::firstOrNew(['phone'=>$request->get('phone')]);
        $user->qq = $qq_info->openid;
        if(!$user->exists){
            $user->nickname = $qq_info->name;
            $user->avatar = $qq_info->headerImg;
            $user->source = 3;//QQ
            $user->password = bcrypt($request->get('password'));
        }
        $user->save();
        if($user->id){
            $login = $this->guard()->loginUsingId($user->id);
            if($login){
                return Output::Success('绑定成功');
            }
        }
        return Output::Fail('绑定失败');
    }
    protected function bindWeixin($request){
        $id = session('wx_id',null);
        $wx_info = ThirdWx::find($id);
        if(is_null($wx_info)){
            return Output::Fail('绑定失败');
        }
        $user = User::firstOrNew(['phone'=>$request->get('phone')]);
        $user->wechat = $wx_info->openid;
        if(!$user->exists){
            $user->nickname = $wx_info->name;
            $user->avatar = $wx_info->headerImg;
            $user->source = 4;//微信
            $user->password = bcrypt($request->get('password'));
        }
        $user->save();
        if($user->id){
            $login = $this->guard()->loginUsingId($user->id);
            if($login){
                return Output::Success('绑定成功');
            }
        }
        return Output::Fail('绑定失败');
    }
}