@extends('backstage.layouts.body')
@section('title', '')
@section('style')

@endsection
@section('content')
    <div class="page-content">
        <div class="page-header">
            <h1>
                {{$mode=='create'?'添加':''}}用户{{$mode=='edit'?'编辑':''}}{{$mode=='show'?'详情':''}}
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
                <form class="form-horizontal" id="" action="/backstage/user{{$mode=='edit'?'/'.$item->id:''}}"
                      method="post">
                    {{csrf_field()}}
                    @if($mode=='edit')
                        {{method_field('PUT')}}
                        <input type="hidden" name="id" value="{{$item->id}}">
                    @elseif($mode=='create')
                        {{method_field('POST')}}
                    @endif
                    <input type="hidden" value="{{request('type',0)}}" name="type">
                    <div>
                        <div class="space-2"></div>
                        <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-1 no-padding-right" for="avatar">头像</label>
                            <div class="col-xs-12 col-sm-11">
                                <div class="input-group">
                                    <div id="avatar" upload-name="avatar" class="image-container"></div>
                                </div>
                            </div>
                        </div>
                        <div class="space-2"></div>
                        <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-1 no-padding-right" for="nickname">名称</label>
                            <div class="col-xs-12 col-sm-11">
                                <input type="text" name="nickname" value="{{$item->nickname}}"
                                       class="col-xs-12 col-sm-6">
                            </div>
                        </div>
                        @if($item->type==1)
                        <div class="space-2"></div>
                        <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-1 no-padding-right" for="phone">房间</label>
                            <div class="col-xs-12 col-sm-11">
                                <select name="room_id" class="col-xs-12 col-sm-6">
                                    <option>请选择</option>
                                    @foreach(\App\Models\Room::where('state',1)->get() as $room)
                                        <option {{$room->id==$item->room_id?'selected="selected"':''}} value="{{$room->id}}">{{$room->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
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
    <script type="text/javascript" src="/box/plug-in/upload/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/box/plug-in/upload/jquery.fileupload.js"></script>

    <script>
                @if(request('type')>0)
        var url = '/backstage/user?type=1';
                @else
        var url = '/backstage/user';
        @endif
$(function () {
            //初始化配置...
            uploadImageBox($('#avatar'), '/upload?category=avatar', '{{$item->avatar}}', 80);


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