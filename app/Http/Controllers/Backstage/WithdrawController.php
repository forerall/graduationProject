<?php

namespace App\Http\Controllers\Backstage;

use App\Models\Withdraw;
use App\Services\BalanceService;
use App\Tools\Output;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WithdrawController extends Controller
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
        $list = (new Withdraw())->newQuery();

        if ($state) {
            $list->where('state', $state);
        }
        if ($keyword) {
            $list->where('name', 'like','%'.$keyword.'%');
        }
        $list = $list->orderBy('id', 'asc')->paginate();
        $list->appends($request->except('page', 'per_page'));
        Withdraw::where('read', 0)->update(['read' => 1]);
        return view('backstage.withdraw.list')
            ->with('list', $list);
    }

    public function show($id)
    {
        $item = Withdraw::findOrFail($id);
        return view('backstage.withdraw.show')
            ->with('item', $item)
            ->with('mode', 'show');
    }

    public function withdrawConfirm(Request $request)
    {
        $id = $request->get('id');
        $msg = $request->get('msg');
        $ok = $request->get('ok');
        $b = Withdraw::findOrFail($id);
        if ($b->state != 1) {
            return Output::Fail('申请状态错误，请刷新页面重试');
        }
        $msg = $ok == 1 ? '提现成功' : '提现失败';
        $result = $this->balanceService->withdrawConfirm($b, $ok == 1, $msg);
        return Output::Result($result, '操作成功', '操作失败');
    }

}
