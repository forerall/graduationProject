@extends('layouts.appLayout')
@section('style')
    <link href="/statics/css/account.css" rel="stylesheet" type="text/css"/>
    <link href="/statics/css/envelope.css" rel="stylesheet" type="text/css"/>
    <link href="/statics/css/footer.css" rel="stylesheet" type="text/css"/>
    <style>
        body{
            background: #eee;
        }
    </style>
@endsection

@section('script')
    <script>
        var app = new App(function () {

        })
    </script>
@endsection

@section('content')
<div class="top">
    <div class="tab-3 top_img">
        <div class="top_img-1">1星用户</div>
        <div class="top_img-2">
            <img src="http://4925.iiio.top/attachment/images/1/2019/01/Vrd9dd2tDRtEvaP6f91o16Odo99OoF.png">
        </div>
        <div class="top_img-3">
            <div>直推人数:0</div>
            <div>团队人数:0</div>
        </div>
    </div>
    <div class="nick_name">13430231551</div>
    <!-- <div class="nick_name"> ID: 810589 </div> -->
    <div class="all_text">累计收益</div>
    <div class="all_num">0.00</div>
    <div class="top_btm flex flex_between tab-3">
        <div class="top_btm_list">
            <div>预备金:0.00</div>
        </div>
        <div class="top_btm_list">
            <div>自由金:0.00</div>
        </div>
        <div class="top_btm_list">
            <div>待发利润:0.00</div>
        </div>
    </div>
</div>
<div class="record">
        <div class="list flex flex_between" go-page="/code">
            <div class="list_img">
                <img src="/statics/images/ct1.png" alt="">
            </div>
            <div class="list_name">我的推荐码</div>
        </div>
        <div class="sh_arrow sh_gray sh_right"></div>
    </div>

    <div class="list flex flex_between" go-page="/proxy">
        <div class="list_lf flex flex_1 ">
            <div class="list_img">
                <img src="/statics/images/ct2.png" alt="">
            </div>
            <div class="list_name">团队信息</div>
        </div>
        <div class="sh_arrow sh_gray sh_right"></div>
    </div>

    <div class="list flex flex_between">
        <div class="list_lf flex flex_1 ">
            <div class="list_img">
                <img src="/statics/images/ct3.png" alt="">
            </div>
            <div class="list_name">抢包记录</div>
        </div>
        <div class="sh_arrow sh_gray sh_right"></div>
    </div>

    <div class="list flex flex_between">
        <div class="list_lf flex flex_1 ">
            <div class="list_img">
                <img src="/statics/images/ct4.png" alt="">
            </div>
            <div class="list_name">充值记录</div>
        </div>
        <div class="sh_arrow sh_gray sh_right"></div>
    </div>

    <div class="list flex flex_between">
        <div class="list_lf flex flex_1 ">
            <div class="list_img">
                <img src="/statics/images/ct5.png" alt="">
            </div>
            <div class="list_name">兑换记录</div>
        </div>
        <div class="sh_arrow sh_gray sh_right"></div>
    </div>

    @include('layouts.footerLayout')
</div>
@endsection
