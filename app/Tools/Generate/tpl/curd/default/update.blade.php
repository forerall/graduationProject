@extends('__Layout__')
@section('title', '')
@section('style')
    __Style__
@endsection
@section('content')
    <div class="page-content">
        <div class="page-header">
            <h1>
                {{$mode=='create'?'添加':''}}__Name__{{$mode=='edit'?'编辑':''}}{{$mode=='show'?'详情':''}}
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
                <form class="form-horizontal" id="" action="__BaseRoute__{{$mode=='edit'?'/'.$item->id:''}}" method="post">
                    {{csrf_field()}}
                    @if($mode=='edit')
                        {{method_field('PUT')}}
                        <input type="hidden" name="id" value="{{$item->id}}">
                    @elseif($mode=='create')
                        {{method_field('POST')}}
                    @endif
                    <div>
                        __InputGroup__
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
    __Javascript__
    <script>
    var url = '__BaseRoute__';
    $(function(){
            //初始化配置...
            __ConfigJs__

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