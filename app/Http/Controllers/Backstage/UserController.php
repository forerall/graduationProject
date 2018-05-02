<?php

namespace App\Http\Controllers\Backstage;

use App\Models\Packet;
use App\Services\BalanceService;
use App\Tools\Output;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        $type = $request->get('type', 0);
        $list = (new User())->newQuery();

        if ($type) {
            $list->where('type', $type);
        } else {
            $list->where('type', 0);
        }

        if ($keyword) {
            $list->where('id', $keyword);
        }

        $list = $list->orderBy('id', 'desc')->paginate(10);

        $str = date('Y-m-d 03:00:00', time());
        $h = date('h', time());
        $t = Carbon::createFromFormat('Y-m-d H:i:s', $str);
        if ($h >= 3) {
            $t2 = Carbon::createFromFormat('Y-m-d H:i:s', $str)->addDays(1);
        } else {
            $t2 = Carbon::createFromFormat('Y-m-d H:i:s', $str)->subDay(1);
        }
        foreach ($list as $u) {
            $u->packet = Packet::where('user_id', $u->id)->whereBetween('created_at', [$t, $t2])->count();
            $u->packet_money = Packet::where('user_id', $u->id)->whereBetween('created_at', [$t, $t2])->sum('packet_money') / 100;
        }
        $list->appends($request->except('page', 'per_page'));
        $sum = User::sum('balance') / 100;
        return view('backstage.user.list')
            ->with('list', $list)
            ->with('sum', $sum);
    }

    public function show($id)
    {
        $item = User::findOrFail($id);
        return view('backstage.user.show')
            ->with('item', $item)
            ->with('mode', 'show');
    }

    public function edit($id)
    {
        $item = User::findOrFail($id);
        return view('backstage.user.update')
            ->with('item', $item)
            ->with('mode', 'edit');
    }

    public function create()
    {
        $item = new User();
        return view('backstage.user.update')
            ->with('item', $item)
            ->with('mode', 'create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nickname' => 'required',
            'rate' => 'required',
        ], [
            'nickname.required' => '名称不能为空',
            'rate.required' => '胜率不能为空',
        ]);
        $item = new User();
        $data = $request->only('avatar', 'nickname', 'rate', 'type', 'room_id');
//        if(array_key_exists('balance',$data)){
//            $data['balance'] = intval($data['balance']*100);
//        }
        $data = array_filter($data, function ($v) {
            return !empty($v);
        });
        $item->fill($data);
        $result = $item->save();

        return Output::Result($result, '添加成功', '添加失败');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nickname' => 'sometimes|required',
        ], [
            'nickname.required' => '名称不能为空',
        ]);
        $item = User::findOrFail($id);
        $data = $request->only('avatar', 'nickname', 'rate', 'room_id');
        $data = array_filter($data, function ($v) {
            return !is_null($v);
        });
//        if(array_key_exists('balance',$data)){
//            $data['balance'] = intval($data['balance']*100);
//        }
        $item->fill($data);
        $result = $item->save();

        return Output::Result($result, '保存成功', '保存失败');
    }

    public function destroy($id)
    {
        $item = User::findOrFail($id);
        $result = $item->delete();

        return Output::Result($result, '删除成功', '删除失败');
    }

    public function jingyong($id)
    {
        $item = User::findOrFail($id);
        $item->state = $item->state == 0 ? 1 : 0;
        $result = $item->save();

        return Output::Result($result, '操作成功', '操作失败');
    }

    public function userup(Request $request)
    {
        $uid = $request->get('uid');
        $fen = intval($request->get('fen') * 100);
        $user = User::findOrFail($uid);
        $fen = abs($fen);
        if ($fen != 0) {
            try {
                DB::beginTransaction();
                $balanceService = new BalanceService();
                if ($fen > 0) {
                    $balanceService->recharge($uid, $fen, '充值成功');
                } else {
                    $balanceService->pay($uid, $fen, '提现成功');
                }
                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error($e);
                return Output::Fail('操作失败，' . $e->getMessage());
            }
        }
        return Output::Success('操作成功');
    }

    public function userdown(Request $request)
    {
        $uid = $request->get('uid');
        $fen = intval($request->get('fen') * 100);
        $user = User::findOrFail($uid);
        $fen = -abs($fen);
        if ($fen != 0) {
            try {
                DB::beginTransaction();
                $balanceService = new BalanceService();
                if ($fen > 0) {
                    $balanceService->recharge($uid, $fen, '充值成功');
                } else {
                    $balanceService->pay($uid, $fen, '提现成功');
                }
                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error($e);
                return Output::Fail('操作失败，' . $e->getMessage());
            }
        }
        return Output::Success('操作成功');
    }

}
