@extends('backstage.layouts.body')
@section('title', '')
@section('style')

@endsection
@section('content')
    <div class="page-content">
        <div class="page-header">
            <h1>
                {{$mode=='create'?'添加':''}}房间{{$mode=='edit'?'编辑':''}}{{$mode=='show'?'详情':''}}
                <small>
                    <i class="icon-double-angle-right"></i>
                </small>
            </h1>
        </div>
        <div class="row">
            <div class="col-xs-12">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form class="form-horizontal" id="" action="/backstage/room{{$mode=='edit'?'/'.$item->id:''}}"
                      method="post">
                    {{csrf_field()}}
                    @if($mode=='edit')
                        {{method_field('PUT')}}
                        <input type="hidden" name="id" value="{{$item->id}}">
                    @elseif($mode=='create')
                        {{method_field('POST')}}
                    @endif
                    <div>
                        <div class="space-2"></div>
                        <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-1 no-padding-right" for="name">名称</label>
                            <div class="col-xs-12 col-sm-11">
                                <input type="text" name="name" value="{{$item->name}}" class="col-xs-12 col-sm-6">
                            </div>
                        </div>

                        <div class="space-2" style="display: none"></div>
                        <div class="form-group" style="display: none">
                            <label class="control-label col-xs-12 col-sm-1 no-padding-right" for="packets">包数</label>
                            <div class="col-xs-12 col-sm-11">
                                <input type="text" name="packets" value="{{$item->packets}}" class="col-xs-12 col-sm-6">
                            </div>
                        </div>
                        <div class="space-2" style="display: none"></div>
                        <div class="form-group" style="display: none">
                            <label class="control-label col-xs-12 col-sm-1 no-padding-right" for="peilv">赔率</label>
                            <div class="col-xs-12 col-sm-11">
                                <input type="text" name="peilv" value="{{$item->peilv}}" class="col-xs-12 col-sm-6">
                            </div>
                        </div>
                        <div class="space-2"></div>
                        <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-1 no-padding-right" for="min">最小金额</label>
                            <div class="col-xs-12 col-sm-11">
                                <input type="text" name="min" value="{{$item->min_str}}" class="col-xs-12 col-sm-6">
                            </div>
                        </div>
                        <div class="space-2"></div>
                        <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-1 no-padding-right" for="max">最大金额</label>
                            <div class="col-xs-12 col-sm-11">
                                <input type="text" name="max" value="{{$item->max_str}}" class="col-xs-12 col-sm-6">
                            </div>
                        </div>
                        <div class="space-2"></div>
                        <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-1 no-padding-right" for="max">房间密码</label>
                            <div class="col-xs-12 col-sm-11">
                                <input type="text" name="password" value="{{$item->password}}" class="col-xs-12 col-sm-6">
                            </div>
                        </div>
                        <div class="space-2"></div>
                        <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-1 no-padding-right" for="state">状态</label>
                            <div class="col-xs-12 col-sm-11">
                                <select name="state" class="col-xs-6 col-sm-2">
                                    @forelse(App\Models\Room::$stateArray as $k=>$v)
                                        <option value="{{$k}}"{{$item->state==$k? ' selected="selected"':''}}>{{$v}}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-1 col-xs-12 col-sm-11">
                            <div class="btn btn-prev event-back">
                                <i class="icon-arrow-left"></i>
                                返回
                            </div>
                            @if($mode=='edit')
                                <div class="btn btn-prev" id="submitForm">保存</div>
                            @elseif($mode=='create')
                                <div class="btn btn-prev" id="submitForm">添加</div>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
@section('script')

    <script>
        var url = '/backstage/room';
        $(function () {
            //初始化配置...


            //提交表单...
            $('#submitForm').click(function () {
                $(this).closest('form').ajaxSubmit(function (data) {
                    ajaxProcess(data, function () {
                        location.reload();
                    });
                });
            })
        })
    </script>
@stop