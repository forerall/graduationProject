<?php

namespace App\Http\Controllers\Backstage;

use App\Tools\Output;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{

    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        $list = (new Admin())->newQuery();

        if ($keyword) {
            $list->where('name', 'like', '%' . $keyword . '%');
        }
        $list = $list->paginate();
        $list->appends($request->except('page', 'per_page'));
        return view('backstage.admin.list')
            ->with('list', $list);
    }

    public function show($id)
    {
        $item = Admin::findOrFail($id);
        return view('backstage.admin.show')
            ->with('item', $item)
            ->with('mode', 'show');
    }

    public function edit($id)
    {
        $item = Admin::findOrFail($id);
        return view('backstage.admin.update')
            ->with('item', $item)
            ->with('mode', 'edit');
    }

    public function create()
    {
        $item = new Admin();
        return view('backstage.admin.update')
            ->with('item', $item)
            ->with('mode', 'create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:g_admin',
            'password' => 'required',
        ], [
            'name.required' => '账号不能为空',
            'name.unique' => '账号已存在',
            'password.required' => '密码不能为空',
        ]);
        $item = new Admin();
        $data = $request->only('name', 'email', 'avatar', 'password');
        $data = array_filter($data, function ($v) {
            return !empty($v);
        });
        $item->fill($data);
        if(isset($data['password'])){
            $item->password = bcrypt($data['password']);
        }
        $result = $item->save();

        return Output::Result($result, '添加成功', '添加失败');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => ['sometimes','required',Rule::unique('g_admin')->ignore($id)],
        ], [
            'name.' => '账号不能为空',
            'name.unique' => '账号已存在',
        ]);
        $item = Admin::findOrFail($id);
        $data = $request->only('name', 'email', 'avatar', 'password');
        $data = array_filter($data, function ($v) {
            return !empty($v);
        });
        $item->fill($data);
        if(isset($data['password'])){
            $item->password = bcrypt($data['password']);
        }
        $result = $item->save();

        return Output::Result($result, '保存成功', '保存失败');
    }

    public function destroy($id)
    {
        $item = Admin::findOrFail($id);
        if($item->isAdmin()){
            return Output::Fail('没有权限');
        }
        $result = $item->delete();

        return Output::Result($result, '删除成功', '删除失败');
    }

}
