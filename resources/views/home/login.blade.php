@extends('layouts.app')

@section('style')
    <style>
        .login-page {
            min-height: 100%;
            position: relative;
        }

        .view {
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }
        .login-page .banner {
            /*background-image: url(https://m.ttbike.com.cn/ebike-h5/latest/static/img/banner.25ab1cd.jpg);*/
            padding-top: 49.6%;
        }
        .bg-image {
            background: transparent 50%/cover no-repeat;
        }

        .login-page .form-container {
            padding-left: 15px;
            background-color: #fff;
        }
        .login-page .form-container .tel-container {
            border-bottom: 1px solid #eee;
        }

        .login-page .form-container .code-container, .login-page .form-container .tel-container {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            color: #646471;
            height: 50px;
        }
        .login-page .form-container .tel-container .tel-input {
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
        }

        .login-page .form-container .code-container input, .login-page .form-container .tel-container input {
            font-size: 16px;
            color: #646471;
            border: none;
        }
        .login-page .form-container .code-container>*, .login-page .form-container .tel-container>* {
            display: block;
        }
        .login-page .form-container .tel-container .send-btn[disabled] {
            background-color: #fff!important;
            color: #b2b2b2!important;
        }

        .login-page .form-container .tel-container .send-btn {
            border: none!important;
            border-left: 1px solid #eee!important;
            width: 125px;
            background-color: #fff;
            color: #646471;
            font-size: 16px;
            margin-left: 0px !important;
        }
        .login-page .form-container .code-container, .login-page .form-container .tel-container {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            color: #646471;
            height: 50px;
        }
        .login-page .form-container .code-container input, .login-page .form-container .tel-container input {
            font-size: 16px;
            color: #646471;
            border: none;
        }
        .login-page .code-container .code-input {
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
        }
        .login-page .btn-container {
            padding: 0 15px;
            margin: 20px 0 15px;
        }
        .login-page .login-btn[disabled] {
            background-color: #f55!important;
            color: #f2f2f5!important;
        }

        .login-page .login-btn {
            border-radius: 25px;
            font-size: 20px;
        }
        .login-page .caution {
            width: 100%;
            margin-top: 10px;
            margin-bottom: 15px;
            font-size: 12px;
            color: #b2b2b2;
            text-align: center;
            padding-left: 15px;
        }
        .login-page .caution a {
            color: #51a1f3;
        }

        a {
            text-decoration: none;
        }

        button, input, textarea {
            outline: none;
        }
        body, html {
            width: 100%;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

    </style>
@endsection
@section('content')

    <div class="login-page view">
        <form action="/login" method="post">
        {{csrf_field()}}
            <input type="hidden" name="recom_id" value="{{request('recom_id')}}">
        <div class="bg-image ">
            <img src="/images/top.png" style="width: 100%;">
        </div>
        <div class="form-container">
            <div class="tel-container"><input placeholder="输入手机号" name="phone" type="tel" maxlength="11" class="tel-input">
                <div disabled="disabled" style="border-radius: 0px !important;line-height: 30px;" class="btn send-btn" data-send="1" id="sendSms" onclick="sendRegisterSms()">发送验证码
                </div>
            </div>
            <div class="code-container"><input type="tel" name="code" placeholder="输入验证码" maxlength="4" class="code-input"></div>
        </div>

        <div class="btn-container">
            <button type="button" id="submitForm" class="btn btn-block btn-danger login-btn">开始
            </button>
        </div>
        </form>
        <div class="caution">点击开始, 即表示同意
            <a href="#/info/agreement" class="">《服务条款》</a>
        </div>
@endsection


@section('script')

    <script>
        function countDown(second){
            if(typeof second == 'undefined'){
                if(sessionStorage){
                    var now = (new Date()).valueOf();
                    now = Math.floor(now/1000);
                    second = now - sessionStorage.getItem('send_begin',now);
                    second = 60 - second;
                }
            }
            if(second>0){
                $('#sendSms').html('已发送(<span id="second">'+second+'</span>)').data('send',0);
                var t = setInterval(function(){
                    second--;
                    if(second<=0){
                        clearInterval(t);
                        $('#sendSms').html('获取验证码').data('send',1);
                    }
                    $('#second').html(second);

                },1000);
            }


        }
        function sendRegisterSms(){
            var phone = $('input[name="phone"]').val();
            if( $('#sendSms').data('send')!=1){
                return;
            }
            var data = {phone:phone,_method:'POST'};
            $.ajax({
                url:'/sendCode',
                data:data,
                dataType:'json',
                type:'POST',
                success:function(data,status,xhr){
                    if(data.errCode == 1){
                        alert(data.errMsg);
                    }else if(data.errCode == 0){
                        if(sessionStorage){
                            var timestamp = (new Date()).valueOf();
                            timestamp = Math.floor(timestamp/1000);
                            sessionStorage.setItem('send_begin',timestamp);
                        }
                        countDown(60);
                    }else if(data.errCode == 2){
                        for(i in data.data.error){
                            alert(data.data.error[i]);return false;
                        }
                    }
                },
                error: function (xhr, status, error) {
                    //ajaxProcess(xhr.status,xhr.responseText);
                    console.log('ajax error:'+xhr.status);
                }
            });
        }
        $(function () {
            $('#submitForm').click(function(){
                $(this).closest('form').ajaxSubmit(function(data){
                    ajaxProcess(data,function(){
                        location.href = '/';
                    });
                });
            })
        })

    </script>
@endsection
