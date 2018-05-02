@extends('layouts.app')

@section('style')
    <style>
        .t1{
            background-color: #fff;
            font-size: 12px;
            padding: 15px 3% 5px 3%;
            color: rgb(112,112,123);
        }
        .t1 input{
            padding: 5px;
            border: none;
            outline: none;
            height: 40px;
            font-size: 20px;
            margin-top: 5px;
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
    <div style="background-color: #ffffff;height: 100vh">
        <form action="/charge" method="post">
            <div class="title">充值</div>
        <div class="t1">
            <div>
                <input name="money" placeholder="输入充值金额">
            </div>
        </div>
        <div style="height: 5px; background-color: rgb(242,242,242);"></div>
        <div class="t2">支付方式</div>
        <div class="pay-select">
            <div class="wx"></div>
            <div class="pay-txt">微信支付</div>
            <div class="cho"></div>
        </div>

        <br>
        <br>
        <br>
        <div class="btn" id="submitForm">去充值</div>
        </form>
    </div>

@endsection


@section('script')

    <script>
        $(function () {
            $('#submitForm').click(function(){
                $(this).closest('form').ajaxSubmit(function(data){

                });
            })
        })

    </script>
@endsection
