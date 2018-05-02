@extends('layouts.app')

@section('style')
    <style>
        .t1{
            background-color: #fff;
            font-size: 12px;
            padding: 15px 3%;
            color: rgb(112,112,123);
        }
        .t1 .y{
            color: rgb(100,100,112);
            float: right;
            font-size: 20px;
            margin-top: -19px;
        }
        .t2{
            background-color: #ffffff;
            font-size: 12px;
            padding: 10px 3%;
            color: rgb(112,112,123);
        }
        .pay-select{
            background-color: #fff;
            font-size: 12px;
            padding: 5px 3%;
            color: rgb(112,112,123);
            position: relative;
        }
        .pay-txt{
            display: inline-block;
            vertical-align: top;
            height: 35px;
            font-size: 14px;
            line-height: 43px;
        }
        .tip{
            margin-top: 34px;
            color: #bbb;
            padding: 5px 5%;
            font-size: 12px;
        }

    </style>
@endsection
@section('content')
    <form action="/yj" method="post">
        {{csrf_field()}}
        <div class="title">押金</div>
    <div style="background-color: #ffffff;height: 100vh">
        <div class="t1">
            <div>需充值押金</div>
            <div class="y">￥{{$order->amount_str}}</div>
        </div>
        <div style="height: 5px; background-color: rgb(242,242,242);"></div>
        <div class="t2">支付方式</div>
        <div class="pay-select">
            <div class="wx"></div>
            <div class="pay-txt">微信支付</div>
            <div class="cho"></div>
        </div>

        <div class="tip">押金可退还</div>
        <br>
        <br>
        <br>
        <div class="btn" onclick="callpay()">去充值</div>
    </div>
    </form>

@endsection


@section('script')
    <script type="text/javascript">
        //调用微信JS api 支付
        function jsApiCall()
        {
            WeixinJSBridge.invoke(
                    'getBrandWCPayRequest',
                    <?php echo $jsApiParameters; ?>,
                    function(res){
                        WeixinJSBridge.log(res.err_msg);
                        //alert(res.err_code+res.err_desc+res.err_msg);
                        if(res.err_msg=='get_brand_wcpay_request:ok'){
                            alert('支付成功');
                            location.href = '/';
                        }else{
                            location.reload();
                        }
                        //NaNget_brand_wcpa_request:ok
                    }
            );
        }

        function callpay()
        {
            if (typeof WeixinJSBridge == "undefined"){
                if( document.addEventListener ){
                    document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                }else if (document.attachEvent){
                    document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                    document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                }
            }else{
                jsApiCall();
            }
        }
    </script>
    <script>
        $(function () {
            @if($user->yajin==\App\Services\BoxService::$yajin)
                    location.href = '/';
            return;
            @endif
        })

    </script>
@endsection
