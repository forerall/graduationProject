@extends('backstage.layouts.body')
@section('title', '')
@section('style')

@endsection
@section('content')
    <div class="page-content">
        <div class="page-header">
            <h1>
                编辑
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
                <form class="form-horizontal" id="" action="" method="post">
                    {{csrf_field()}}
                    <div>
                        <div class="space-2"></div>
                        <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-1 no-padding-right" for="avatar">系统公告</label>
                            <div class="col-xs-12 col-sm-11">
                                <textarea name="gg" class="col-xs-6 col-sm-6">{{$gg->value}}</textarea>
                            </div>
                        </div>
                        <div class="space-2"></div>
                        <div class="form-group" style="display: none;">
                            <label class="control-label col-xs-12 col-sm-1 no-padding-right" for="avatar">平台抽水比例（%）</label>
                            <div class="col-xs-12 col-sm-11">
                                <div class="input-group">
                                    <input type="text" name="choushui" value="{{$config->value}}">
                                </div>
                            </div>
                        </div>
                        <div class="space-2"></div>
                        <div class="form-group" style="display: none;">
                            <label class="control-label col-xs-12 col-sm-1 no-padding-right" for="avatar">一级抽水比例（%）</label>
                            <div class="col-xs-12 col-sm-11">
                                <div class="input-group">
                                    <input type="text" name="choushui1" value="{{$config1->value}}">
                                </div>
                            </div>
                        </div>
                        <div class="space-2"></div>
                        <div class="form-group" style="display: none;">
                            <label class="control-label col-xs-12 col-sm-1 no-padding-right" for="avatar">二级抽水比例（%）</label>
                            <div class="col-xs-12 col-sm-11">
                                <div class="input-group">
                                    <input type="text" name="choushui2" value="{{$config2->value}}">
                                </div>
                            </div>
                        </div>
                        <div class="space-2"></div>
                        <div class="form-group" style="display: none;">
                            <label class="control-label col-xs-12 col-sm-1 no-padding-right" for="avatar">三级抽水比例（%）</label>
                            <div class="col-xs-12 col-sm-11">
                                <div class="input-group">
                                    <input type="text" name="choushui3" value="{{$config3->value}}">
                                </div>
                            </div>
                        </div>

                    <div class="form-group">
                        <div class="col-sm-offset-1 col-xs-12 col-sm-11">
                            <div class="btn btn-prev" id="submitForm">保存</div>
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
        var url = '/backstage/cs';
        $(function () {
            //初始化配置...
            //uploadImageBox($('#avatar'), '/upload?category=avatar', '{{$config->value}}', 200);


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