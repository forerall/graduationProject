@extends('layouts.app')

@section('style')
    <style>
        .section{
            margin: 10px 0px;
            background-color: #ffffff;
            padding: 10px 3%;
            font-size: 13px;
        }
        .section > div{
            padding: 3px 0px;
        }
        .coupon{
            background-position-y: 0px;
            padding: 10px 0px !important;
        }
        .coupon-list{
            display: none;
        }
        .coupon-list > div{
            font-size: 14px;
            padding: 10px 3%;
            position: relative;
        }
        .coupon-list > div >span{
            font-size: 12px;
            color: #BBBBBB;
        }
        .select{
            position: absolute;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            border: 1px solid #dddddd;
            box-sizing: border-box;
            background-color: #ffffff;
            right: 10px;
            top: 4px;
        }
        .selected{
            background-color: red;
            border-color: red;
        }
        .coupon-info{
            text-align: right;margin-right: 10px
        }
        .sf{
            text-align: right;margin-right: 10px
        }
        .money{
            color: #FE6714;;
        }
    </style>
@endsection
@section('content')
    <input type="hidden" value="{{$order->order_amount/100}}" name="order_amount">
    <div class="section">
        <div>存包柜：{{$order->terminal->name}}-{{$order->box->number>9?$order->box->number:'0'.$order->box->number}}箱</div>
        <div>存包时间：{{$order->begin_at}}</div>
        <div>取包时间：{{$order->end_at}}</div>
        <div>时长：<span class="total-hour">{{$order->hour}}</span>小时</div>
        <div>总金额：{{$order->order_amount_str}}元</div>
        @if($order->state == 2)
            <div>实付：{{$order->pay_amount_str}}</div>
        @endif
        <div>状态：{{$order->state_str}}</div>
    </div>
    @if($order->state == 2)
    <div class="section">
        <div class="arrow-bg coupon">优惠券</div>
        <div class="coupon-list">
            <div data-coupon-id="0" data-m="0">
                <p>不使用优惠
                </p>
                <div class="select"></div>
            </div>
            @forelse($coupons as $coupon)
                <div data-coupon-id="{{$coupon->id}}" data-m="{{$coupon->money_str}}">
                    <p><span class="money">{{$coupon->money_str}}￥</span>
                        <span style="color: #BBBBBB;font-size:10px;">满<span class="money">{{$coupon->need_str}}</span>元使用</span>
                    </p>
                    <div class="select {{$coupon->id==$order->coupon_id?'selected':''}}"></div>
                </div>
                @empty
                <div>没有可用优惠券</div>
            @endforelse
        </div>
        <div class="coupon-info">{!! $order->coupon_money>0?'<span class="money">-'.($order->coupon_money/100).'</span>元':'' !!}</div>
        <div class="sf">实付：<span class="re-pay money">{{($order->order_amount-$order->coupon_money)/100}}</span>元</div>
    </div>
    <button id="pay" style="width:60%;margin: 20px 20%; height:40px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button">立即支付</button>
    @endif
@endsection


@section('script')
    <script type="text/javascript"
            src="http://api.map.baidu.com/api?v=2.0&ak=LnBnamvKBneCbsAHAWG7aHNVa1L3GUE4"></script>
    <script type="text/javascript">
        //调用微信JS api 支付
        function jsApiCall(jsApiParameters)
        {
            WeixinJSBridge.invoke(
                    'getBrandWCPayRequest',
                    jsApiParameters,
                    function(res){
                        WeixinJSBridge.log(res.err_msg);
                        if(res.err_msg=='get_brand_wcpay_request:ok'){
                            //alert('支付成功');
                            location.href = '/order';
                        }else{
                            location.reload();
                        }
                    }
            );
        }
    </script>
    <script>
        function toDecimal(x) {
            var f = parseFloat(x);
            if (isNaN(f)) {
                return;
            }
            f = Math.round(x*100)/100;
            return f;
        }
        function cal_total(){
            var total = parseFloat($('input[name="order_amount"]').val());
            var coupon = $('.selected').parent();
            var jian = parseFloat(coupon.data('m'));
            var pay = toDecimal(total-jian);
            $('.coupon-info').html('<span class="money">-'+jian+'</span>元');
            $('.re-pay').html(pay);
        }
        $(function () {
            $('.coupon').click(function(){
                if($('.coupon-list').css('display')=='none'){
                    $('.coupon-list').show();
                    $('.coupon-info').hide();
                }else{
                    $('.coupon-list').hide();
                    $('.coupon-info').show();
                }
            })

            $('.select').click(function(){
                $('.select').removeClass('selected');
                $(this).addClass('selected');
                $('.coupon-info').show();
                cal_total();
            })

            $('#pay').click(function(){
                var coupon_id = 0;
                var order_id = '{{$order->id}}';
                if($('.selected').length>0){
                    coupon_id = $('.selected').parent().data('coupon-id');
                }
                $.ajax({
                    url:'/orderPay',
                    data:{order_id:order_id,coupon_id:coupon_id},
                    dataType:'json',
                    type:'POST',
                    success:function(data,status,xhr){
                        if(data.errCode==0){
                            jsApiCall(data.data);
                        }else{
                            alert(data.errMsg);
                        }
                    },
                    error: function (xhr, status, error) {
                        //ajaxProcess(xhr.status,xhr.responseText);
                        console.log('ajax error:'+xhr.status);
                    }
                });
            })
        })

    </script>
@endsection
