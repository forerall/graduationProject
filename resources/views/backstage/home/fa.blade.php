@extends('backstage.layouts.body')
@section('title', '')
@section('style')

@endsection
@section('content')
    <div class="page-content">
        <div class="page-header">
            <h1>
                发福利包
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
                            <label class="control-label col-xs-12 col-sm-1 no-padding-right" for="avatar">房间</label>
                            <div class="col-xs-12 col-sm-11">
                                    <select name="room_id">
                                        @foreach(\App\Models\Room::where('password', '<>', '')->where('state',1)->get() as $room)
                                            <option value="{{$room->id}}">{{$room->name}}</option>
                                        @endforeach
                                    </select>
                            </div>
                        </div>
                        <div class="space-2"></div>
                        <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-1 no-padding-right" for="avatar">金额</label>
                            <div class="col-xs-12 col-sm-11">
                                    <input type="text" name="money" value="" class="col-xs-12 col-sm-6">
                            </div>
                        </div>
                        <div class="space-2"></div>
                        <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-1 no-padding-right" for="avatar">包数</label>
                            <div class="col-xs-12 col-sm-11">
                                <input type="text" name="number" value="" class="col-xs-12 col-sm-6">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-1 col-xs-12 col-sm-11">
                            <div class="btn btn-prev" id="submitForm">发送</div>
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
        var url = '/backstage/fa';
        $(function () {


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