@extends('layouts.app')

@section('style')
    <style>
        .input-container{
            background-color: #ffffff;
            padding: 5px 3%;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
        }
        .input-container input{
            border: none;
            text-indent: 3px;
            height: 30px;
            font-size: 16px;
            color: #646471;
            display: block;
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
        }
    </style>
@endsection
@section('content')
    <div style="padding: 15px 0px;text-align: center;color: #aaaaaa;font-size: 14px">退还押金到支付宝</div>
    @if($item->state == 3)
    <div style="padding: 15px 0px;text-align: center;color: red;font-size: 14px">退还失败原因：{{$item->msg}}</div>
    @endif
    @if($item->state == 1)
        <div style="padding: 15px 0px;text-align: center;color: #aaaaaa;font-size: 14px">申请正在处理中，请耐心等待...</div>
    @endif
    @if($item->state == 2&& $user->yajin<=0 )
        <div style="padding: 15px 0px;text-align: center;color: #aaaaaa;font-size: 14px">押金已退回</div>
    @endif
    <form method="post" action="/backbound">
        <div class="input-container">
            <input type="text" name="name" placeholder="姓名" class="" value="{{$item->name}}">
        </div>
        <div class="input-container">
            <input type="text" name="phone" placeholder="手机号" class="" value="{{$item->phone}}">
        </div>
        <div class="input-container">
            <input type="text" name="zfb" placeholder="支付宝账号" class="" value="{{$item->zfb}}">
        </div>
        @if($item->state != 1&&$item->state != 2)
            <br>
        <div class="btn"  id="submitBtn">提交</div>
        @endif
        @if($user->yajin>0 &&$item->state == 2)
            <br>
            <div class="btn"  id="submitBtn">提交</div>
        @endif
    </form>

@endsection


@section('script')
    <script type="text/javascript">
        $(function(){
            $('#submitBtn').click(function(){
                $(this).closest('form').ajaxSubmit(function(data){
                    alert(data.errMsg);
                    if(data.errCode==0){
                        history.back();
                    }
                });
            })
        })
    </script>
@endsection
