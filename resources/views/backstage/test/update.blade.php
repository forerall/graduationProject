@extends('backstage.layouts.body')
@section('title', '')
@section('style')
    
@endsection
@section('content')
    <div class="page-content">
        <div class="page-header">
            <h1>
                {{$mode=='create'?'添加':''}}测试{{$mode=='edit'?'编辑':''}}{{$mode=='show'?'详情':''}}
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
                <form class="form-horizontal" id="" action="/backstage/test{{$mode=='edit'?'/'.$item->id:''}}" method="post">
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
    <label class="control-label col-xs-12 col-sm-1 no-padding-right" for="article_id">article_id</label>
    <div class="col-xs-12 col-sm-11">
        <input type="text" name="article_id" value="{{$item->article_id}}" class="col-xs-12 col-sm-6">
    </div>
</div>
<div class="space-2"></div>
<div class="form-group">
    <label class="control-label col-xs-12 col-sm-1 no-padding-right" for="area_id">area_id</label>
    <div class="col-xs-12 col-sm-11">
        <input type="text" name="area_id" value="{{$item->area_id}}" class="col-xs-12 col-sm-6">
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
    var url = '/backstage/test';
    $(function(){
            //初始化配置...
            

            //提交表单...
            $('#submitForm').click(function(){
                $(this).closest('form').ajaxSubmit(function(data){
                    ajaxProcess(data,function(){
                        location.reload();
                    });
                });
            })
        })
    </script>
@stop