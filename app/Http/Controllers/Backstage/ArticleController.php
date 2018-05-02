<?php

namespace App\Http\Controllers\Backstage;

use App\Tools\Output;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Article;

class ArticleController extends Controller
{

    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        $list = (new Article())->newQuery();

        if ($keyword) {
            $list->where('name', 'like', '%' . $keyword . '%');
        }
        $list = $list->orderBy('id', 'asc')->paginate();
        $list->appends($request->except('page', 'per_page'));
        return view('backstage.article.list')
            ->with('list', $list);
    }

    public function show($id)
    {
        $item = Article::findOrFail($id);
        return view('backstage.article.show')
            ->with('item', $item)
            ->with('mode', 'show');
    }

    public function edit($id)
    {
        $item = Article::findOrFail($id);
        return view('backstage.article.update')
            ->with('item', $item)
            ->with('mode', 'edit');
    }

    public function create()
    {
        $item = new Article();
        return view('backstage.article.update')
            ->with('item', $item)
            ->with('mode', 'create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            //'type' => 'required',
            'content' => 'required',
        ], [
            'name.required' => '名称不能为空',
            'type.required' => '类型不能为空',
            'content.required' => '内容不能为空',
        ]);
        $item = new Article();
        $data = $request->only('name', 'type', 'state', 'images', 'content');
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
            //'type' => 'sometimes|required',
            'content' => 'sometimes|required',
        ], [
            'name.' => '名称不能为空',
            'type.' => '类型不能为空',
            'content.' => '内容不能为空',
        ]);
        $item = Article::findOrFail($id);
        $data = $request->only('name', 'type', 'state', 'images', 'content');
        $data = array_filter($data, function ($v) {
            return !empty($v);
        });
        $item->fill($data);
        $result = $item->save();

        return Output::Result($result, '保存成功', '保存失败');
    }

    public function destroy($id)
    {
        $item = Article::findOrFail($id);
        $result = $item->delete();

        return Output::Result($result, '删除成功', '删除失败');
    }

}
