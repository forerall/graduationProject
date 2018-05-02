<?php

namespace App\Http\Controllers\Backstage;


use App\Http\Controllers\Controller;

use App\Models\Setting;
use App\Tools\Output;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $list = (new Setting())->newQuery();
        $list = $list->paginate();
        $list->appends($request->except('page', 'per_page'));
        return view('backstage.setting.list')
            ->with('list', $list);
    }

    public function update(Request $request, $id)
    {
        $value = intval($request->get('value'));
        if ($value < 0 || $value > 99) {
            return Output::Fail('参数值0-99');
        }

        $item = Setting::findOrFail($id);
        $item->value = $value;
        $result = $item->save();
        Setting::forgetSetting();

        return Output::Result($result, '修改成功', '修改失败');
    }
}
