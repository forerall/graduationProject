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
    <div class="btn" onclick="callpay()">去充值</div>
@endsection


@section('script')
    <script type="text/javascript">
        //调用微信JS api 支付
        function jsApiCall()
        {
            WeixinJSBridge.invoke(
                    'getBrandWCPayRequest',
                    <?php echo $js; ?>,
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

        })

    </script>
@endsection
