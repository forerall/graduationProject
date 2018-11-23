<?php

namespace App\Http\Controllers\Backstage;

use App\Services\BalanceService;
use App\Services\GameService;
use App\Tools\Output;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class ProxyController extends Controller
{
    public function openProxy(Request $request)
    {
        $uid = $request->get('uid');
        $user = User::findOrFail($uid);
        $user->proxy = 1;
        $user->recommend_user_id = 0;
        $user->recommend_user_id1 = 0;
        $user->recommend_user_id2 = 0;
        return Output::Result($user->save(), '开启成功', '开启失败');
    }

    public function closeProxy(Request $request)
    {
        $uid = $request->get('uid');
        $user = User::findOrFail($uid);
        $user->proxy = 0;
        return Output::Result($user->save(), '关闭成功', '关闭失败');
    }

    public function proxyList(Request $request)
    {
        $keyword = $request->get('keyword');
        $pid = $request->get('pid');

        if ($pid) {
            $list = User::where('recommend_user_id', $pid);
        } else {
            $list = User::where('proxy', '>', 0);
        }
        if ($keyword) {
            $list->where('nickname', 'like', '%' . $keyword . '%');
        }

        $list = $list->orderBy('id', 'desc')->paginate(10);
        $list->appends($request->except('page', 'per_page'));
        return view('backstage.proxy.list')
            ->with('list', $list);
    }

    public function tongji(Request $request)
    {
        $g = new GameService(new BalanceService());
        $uid = $request->get('uid');
        $day = $request->get('day');//dd($day);
        $list = $g->getConsumeRecord($uid, $day);
        return view('backstage.proxy.tongji')
            ->with('list', $list);
    }
}
