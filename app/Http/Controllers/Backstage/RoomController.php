<?php

namespace App\Http\Controllers\Backstage;

use App\Tools\Output;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Room;

class RoomController extends Controller
{

    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        $list = (new Room())->newQuery();

        if ($keyword) {
            $list->where('name', 'like', '%' . $keyword . '%');
        }
        $list = $list->orderBy('id', 'asc')->paginate();
        $list->appends($request->except('page', 'per_page'));
        return view('backstage.room.list')
            ->with('list', $list);
    }

    public function show($id)
    {
        $item = Room::findOrFail($id);
        return view('backstage.room.show')
            ->with('item', $item)
            ->with('mode', 'show');
    }

    public function edit($id)
    {
        $item = Room::findOrFail($id);
        return view('backstage.room.update')
            ->with('item', $item)
            ->with('mode', 'edit');
    }

    public function create()
    {
        $item = new Room();
        return view('backstage.room.update')
            ->with('item', $item)
            ->with('mode', 'create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            //'type' => 'required',
            //'packets' => 'required',
            //'peilv' => 'required',
            'min' => 'required',
            'max' => 'required',
        ], [
            'name.required' => '名称不能为空',
            'type.required' => '类型不能为空',
            'packets.required' => '包数不能为空',
            'peilv.required' => '赔率不能为空',
            'min.required' => '最小金额不能为空',
            'max.required' => '最大金额不能为空',
        ]);
        $item = new Room();
        $data = $request->only('name', 'type', 'packets', 'peilv', 'min', 'max', 'state', 'password');
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
            'name' => 'sometimes|required',
            'type' => 'sometimes|required',
            'packets' => 'sometimes|required',
            'peilv' => 'sometimes|required',
            'min' => 'sometimes|required',
            'max' => 'sometimes|required',
        ], [
            'name.' => '名称不能为空',
            'type.' => '类型不能为空',
            'packets.' => '包数不能为空',
            'peilv.' => '赔率不能为空',
            'min.' => '最小金额不能为空',
            'max.' => '最大金额不能为空',
        ]);
        $item = Room::findOrFail($id);
        $data = $request->only('name', 'type', 'packets', 'peilv', 'min', 'max', 'state', 'password');
        $data = array_filter($data, function ($v) {
            return !empty($v);
        });
        $item->fill($data);
        $result = $item->save();

        return Output::Result($result, '保存成功', '保存失败');
    }

    public function destroy($id)
    {
        $item = Room::findOrFail($id);
        $result = $item->delete();

        return Output::Result($result, '删除成功', '删除失败');
    }

    public function roomState(Request $request)
    {
        $item = Room::findOrFail($request->get('id'));
        $item->state = $item->state == 1 ? 2 : 1;
        $result = $item->save();

        return Output::Result($result, '操作成功', '操作失败');
    }

}
