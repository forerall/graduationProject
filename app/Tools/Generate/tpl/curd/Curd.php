<?php

namespace __Namespace__;

use App\Tools\Output;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
__ModelNamespace__

class __Class__ extends Controller
{

    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        $list = (new __Model__())->newQuery();
        __With__
        if($keyword){
            $list->where('__LikeField__','like','%'.$keyword.'%');
        }
        $list = $list->orderBy('id','asc')->paginate();
        $list->appends($request->except('page','per_page'));
        return view('__View__.list')
            ->with('list',$list)
            ;
    }
    public function show($id)
    {
        $item = __Model__::findOrFail($id);
        return view('__View__.show')
            ->with('item',$item)
            ->with('mode','show')
            ;
    }
    public function edit($id)
    {
        $item = __Model__::findOrFail($id);
        return view('__View__.update')__ViewWithData__
            ->with('item',$item)
            ->with('mode','edit')
            ;
    }
    public function create()
    {
        $item = new __Model__();
        return view('__View__.update')__ViewWithData__
            ->with('item',$item)
            ->with('mode','create')
            ;
    }

    public function store(Request $request)
    {
        __StoreValidate__
        $item = new __Model__();
        $data = $request->only(__FillField__);
        $data = array_filter($data,function($v){return 0!==$v;});
        $item->fill($data);
        $result = $item->save();
        __RelationSave__
        return Output::Result($result,'添加成功','添加失败');
    }
    public function update(Request $request, $id)
    {
        __UpdateValidate__
        $item = __Model__::findOrFail($id);
        $data = $request->only(__FillField__);
        $data = array_filter($data,function($v){return 0!==$v;});
        $item->fill($data);
        $result = $item->save();
        __RelationUpdate__
        return Output::Result($result,'保存成功','保存失败');
    }
    public function destroy($id)
    {
        $item = __Model__::findOrFail($id);
        $result = $item->delete();
        __RelationDelete__
        return Output::Result($result,'删除成功','删除失败');
    }

}
