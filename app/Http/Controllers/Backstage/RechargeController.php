<?php

namespace App\Http\Controllers\Backstage;

use App\Models\Recharge;
use App\Models\Withdraw;
use App\Services\BalanceService;
use App\Tools\Output;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RechargeController extends Controller
{
    protected $balanceService;

    public function __construct(BalanceService $balanceService)
    {
        $this->balanceService = $balanceService;
    }

    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        $state = $request->get('state');
        $list = (new Recharge())->newQuery();

        if ($state) {
            $list->where('state', $state);
        }
        if ($keyword) {
            $list->where('name', 'like', '%' . $keyword . '%');
        }
        $list = $list->orderBy('id', 'asc')->paginate();
        $list->appends($request->except('page', 'per_page'));
        Recharge::where('read', 0)->update(['read' => 1]);

        return view('backstage.recharge.list')
            ->with('list', $list);
    }

    public function show($id)
    {
        $item = Recharge::findOrFail($id);
        return view('backstage.recharge.show')
            ->with('item', $item)
            ->with('mode', 'show');
    }

    public function rechargeConfirm(Request $request)
    {
        $id = $request->get('id');
        $msg = $request->get('msg');
        $ok = $request->get('ok');
        $b = Recharge::findOrFail($id);
        if ($b->state != 1) {
            return Output::Fail('申请状态错误，请刷新页面重试');
        }
        $msg = $ok == 1 ? '充值成功' : '充值失败';
        $result = $this->balanceService->rechargeConfirm($b, $ok == 1, $msg);
        return Output::Result($result, '操作成功', '操作失败');
    }

}
