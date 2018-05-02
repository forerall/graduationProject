<?php

namespace App\Http\Middleware;

use App\Tools\Output;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class ApiAuthSometime
{
    public function __construct()
    {
    }


    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->get('token', false);
        $user_id = $request->get('user_id', false);
        if($token&&$user_id){
            $userInfo = User::where('id', $user_id)->where('remember_token', $token)->first();
            if($userInfo){
                Auth::guard($guard)->loginUsingId($userInfo->id);
            }
        }
        return $next($request);
    }
}
