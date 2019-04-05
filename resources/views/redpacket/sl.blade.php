@extends('layouts.appLayout')
@section('style')
    <link href="/statics/css/sl.css" rel="stylesheet" type="text/css"/>
    <style>

    </style>
@endsection

@section('script')
    <script>
        var app = new App(function () {

        })
    </script>
@endsection

@section('content')
    <div class="header">
        <div>扫雷</div>
        <div class="right-btn">红包记录</div>
    </div>
    <div class="top">
        <div class="box">
            <div>已发金币：<span>32</span></div>
            <div>能抢金币：<span>32</span></div>
            <div>已抢金币：<span>32</span></div>
        </div>
        <div class="box" style="text-align: center">
            <div>宝箱</div>
            <div>2380932</div>
        </div>
        <div class="box" style="text-align: right">
            <div>预备金：<span>32</span></div>
            <div>自由金：<span>32</span></div>
            <div>发红包</div>
        </div>
    </div>
    <div class="redpacket">
        <div class="head">
            <div class="countdown"><span class="J_minute">02</span>:<span class="J_second">43</span></div>
        </div>
        <div class="record-top">
            <img class="record-avatar"
                 src="http://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83eqPDc9apoGicQ8KOzrnB4rhUyrIrSKLgicXUlnZq1zLcFsq9LgtGOVMnAdRX12FkgE8ib71tBKTXsA5g/132"
                 style="border: 3px solid rgb(225,202,170)">
            <img class="record-top-bg" src="/statics/images/record-top.png">

            <p class="nickname"><span>麦田的红包</span></p>
            <p>
                <span>2级</span>
                <span>329金币</span>
            </p>
        </div>
        <div class="qiang-state">10/3已抢</div>
        <div class="btns">
            <div>匿名抢</div>
            <div>下一个</div>
            <div>实名抢</div>
        </div>
    </div>


    @include('layouts.footerLayout')
@endsection
