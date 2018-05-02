@extends('layouts.app')
@section('style')
    <style>
        .registerError{
            text-align: center;
            margin-top: -10px;
            margin-bottom: 5px;
            color: red;
        }
        label.error{
            width: 475px;
        }
        .need-pass{
            display: none;
        }
        @media screen and (max-width: 768px) {
            .card-info-r{
                display: block !important;
                width: 475px !important;
                float: none !important;
                margin: 0 auto;
                text-align: left;
            }

        }
    </style>
@endsection
@section('content')
    <form id="registerForm2" action="/bindInfo" method="POST">
        {{csrf_field()}}
        <input type="hidden" name="type" value="{{$type}}">
        <div class="card-box">
            <div class="card-title">欢迎来到无界艺术版权库！请完善你的信息</div>
            <div class="card-info clearfix">
                <div class="card-info-l">登录账号</div>
                <div class="card-info-m clearfix">
                    <input name="phone" value="{{old('phone')}}" class="input-all public-input" type="text" placeholder="手机号">
                </div>
                <div class="card-info-r">
                </div>
            </div>
            <div class="card-info clearfix">
                <div class="card-info-l">验证码</div>
                <div class="card-info-m clearfix">
                    <input name="code" class="input-yz public-input" type="text" placeholder="输入验证码">
                    <div style="text-align: center" data-send="1" onclick="javascript:sendBindSms()" id="sendSms" class="button-yz public-button">获取验证码</div>
                </div>
                <div class="card-info-r">
                </div>
            </div>
            <div class="card-info clearfix need-pass">
                <div class="card-info-l">登录密码</div>
                <div class="card-info-m clearfix">
                    <input name="password" id="password" class="input-all public-input" type="password" placeholder="登录密码">
                </div>
                <div class="card-info-r">
                </div>
            </div>
            <div class="card-info clearfix need-pass">
                <div class="card-info-l">确认密码</div>
                <div class="card-info-m clearfix">
                    <input name="password_confirm" class="input-all public-input" type="password" placeholder="确认密码">
                </div>
                <div class="card-info-r">
                </div>
            </div>
            <div class="card-info clearfix">
                <div class="card-info-l">&nbsp;</div>
                <div class="card-info-m clearfix" style="font-size: 14px;line-height: 20px;height: 30px;">
                    <input name="agree" class="agree" type="checkbox">我已阅读并接受<a href="/agreeReg" style="color: rgb(0, 0, 238);">《平台注册协议》</a>和<a href="/agreeAuth"  style="color: rgb(0, 0, 238);">《版权授权协议》</a>
                </div>
                <div class="card-info-r">
                </div>
            </div>
            <p class="registerError">{{$errors->count()>0?$errors->all()[0]:''}}</p>
            <div class="card-btn-confirm public-button">
                <button style="background-color: black;color: #ffffff;font-size: 24px;width: 100%;height: 100%;cursor: pointer">确认</button>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script src="/js/box/jquery.form.js"></script>
    <script>
        function refreshCode(){
            $('#captcha_value').val('').focus();
            $('#captcha').attr('src','/captcha?r='+Math.random());
        }
        function countDown(second){
            if(typeof second == 'undefined'){
                if(sessionStorage){
                    var now = (new Date()).valueOf();
                    now = Math.floor(now/1000);
                    second = now - sessionStorage.getItem('send_begin',now);
                    second = 120 - second;
                }
            }
            if(second>0){
                $('#sendSms').html('验证码已发送<span id="second">('+second+')</span>').data('send',0);
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
        function sendBindSms(){
            var phone = $('input[name="phone"]').val();
            if( $('#sendSms').data('send')!=1){
                return;
            }
            ajax('/sendBindCode',{phone:phone},'POST',function(data){
                if(data.errCode==0){
                    if(sessionStorage){
                        var timestamp = (new Date()).valueOf();
                        timestamp = Math.floor(timestamp/1000);
                        sessionStorage.setItem('send_begin',timestamp);
                    }
                    countDown(120);
                    if(data.data.userExist>0){
                        $('.need-pass').hide();
                        $('.need-pass').find('input').val('123456');
                    }else{
                        $('.need-pass').show();
                        $('.need-pass').find('input').val('');
                    }
                }else{
                    $('.registerError').html(data.errMsg);
                }
            });
        }
        function submitCallback(data){
            if(data.errCode==0){
                location.href = '/home';
            }else{
                $('.registerError').html(data.errMsg);
            }
        }
        $(function(){
            countDown();
            $("#registerForm2").validate({
                ignore:'',//默认忽略:hidden
                rules: {
                    code: {required:true,minlength:4},
                    phone: "required",
                    name: "required",
                    password: "required",
                    password_confirm: {
                        required:true,
                        equalTo: "#password"
                    },
                    agree: "required"
                },
                messages:{
                    phone:{
                        required:'请输入手机号'
                    },
                    code:{
                        required:'请输入验证码',
                        minlength:'验证码最少4位'
                    },
                    name:{
                        required:'请输入昵称'
                    },
                    password:{
                        required:'请输入密码'
                    },
                    password_confirm:{
                        required:'两次输入的密码不一致',
                        equalTo:'两次输入的密码不一致'
                    },
                    agree:{
                        required:'未接受注册协议'
                    }
                },
                errorPlacement: function(error, element) {
                    $( element).parent().parent().find('.card-info-r').append( error );
                },submitHandler:function(form){
                    $(form).ajaxSubmit({
                        success:function(data,status,xhr){
                            submitCallback(data);
                        },
                        error:function(xhr, status, error){
                            submitCallback({
                                errCode :1,
                                errMsg:'',
                                data:xhr.responseJSON,
                                status:xhr.status
                            });
                        }
                    });
                }
            });

        });
    </script>
@stop