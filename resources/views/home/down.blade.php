@extends('layouts.app')

@section('style')
    <style>
        .upload-btn{
            color: blue;
            font-size: 16px;
            text-align: center;
        }

    </style>
@endsection
@section('content')
    <div class="fixed-header header">
        <h1>下分</h1>
        <i class="iconfont icon-back" onclick="history.go(-1)"></i>
    </div>
    <div class="content">
        <div style="padding: 1rem 0.5rem 0;">
            当前积分：{{$user->balance_str}}
        </div>
        <div class="form-box">
            <form action="/down" method="post">
                {{csrf_field()}}
                <div class="input-group">
                    <div class="input-label">账号类型</div>
                    <div class="input-content">
                        <select name="pay_way">
                            <option value="alipay">支付宝</option>
                            <option value="wxpay">微信</option>
                        </select>
                    </div>
                </div>
                <div class="input-group" style="display: none">
                    <div class="input-label">账号</div>
                    <div class="input-content">
                        <input name="account" type="text">
                    </div>
                </div>
                <div class="input-group">
                    <div class="input-label">金额</div>
                    <div class="input-content">
                        <input name="money" type="text">
                    </div>
                </div>
                <div class="input-group">
                    <div class="input-label">收款二维码</div>
                    <div class="input-content" style="height: initial">
                        <div id="sk" upload-name="sk" class="image-container"></div>
                    </div>
                </div>
                <div class="input-group">
                    <div class="btn submit">提交</div>
                </div>
            </form>
        </div>
    </div>
@endsection


@section('script')
    <script type="text/javascript" src="/box/plug-in/upload/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/box/plug-in/upload/jquery.fileupload.js"></script>
    <script>
        $(function () {
            uploadImageBox($('#sk'), '/upload?category=avatar', '', 200);

            $('.submit').click(function () {
                var _this = $(this)
                _this.closest('form').ajaxSubmit(function (data) {
                    if (data.errCode == 0) {

                        alert(data.errMsg)
                        location.reload()
                    } else if(data.errCode==2){
                        for(i in data.data.error){
                            alert(data.data.error[i]);
                            break;
                        }
                    }else{
                        alert(data.errMsg)
                    }
                })
            })
        })

    </script>
@endsection
