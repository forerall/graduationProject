@extends('layouts.appLayout')
@section('style')
    <link href="/statics/css/index.css" rel="stylesheet" type="text/css"/>
    <link href="/statics/css/envelope.css" rel="stylesheet" type="text/css"/>
    <link href="/statics/css/footer.css" rel="stylesheet" type="text/css"/>
    <style>
        body{
            background: #1C1C1C;
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

    <div style="padding: 10px;">
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
                    <div class="top_btm_text">预备金</div>
                    <div class="top_btm_num">90489.33</div>
                </div>
                <div class="top_btm_list">
                    <div class="top_btm_text">自由金</div>
                    <div class="top_btm_num">0.00</div>
                </div>
                <div class="top_btm_list">
                    <div class="top_btm_text">今日收益</div>
                    <div class="top_btm_num">0</div>
                </div>
            </div>
        </div>

        <div class="notice flex flex_between">
            <div class="notice_con">
                <img src="/statics/images/info.png" alt="">
            </div>
            <marquee class="flex flex_1 flex_col_center" behavior="" direction="">
                <ul class=" list-paddingleft-2" style="list-style-type: disc;">
                    <li>
                        <p style="text-align: left;">
                    <span class="notice-font">
                        红包矿工同时支持 ETH USDT 数字资产充值，所有充值提现均在链上完成，24小时随时充提，约10分钟到账，安全放心；如有疑问，可扫描添加客服询问
                    </span>
                        </p>
                    </li>
                </ul>
            </marquee>
        </div>

        <div class="content">
            <div class="play">
                <a href="javascript:void(0);"> <img src="/statics/images/index11.png" alt=""></a>
            </div>
            <div class="play">
                <a href="javascript:void(0);"> <img src="/statics/images/index11.png" alt=""></a>
            </div>
            <div class="play">
                <a href="javascript:void(0);"> <img src="/statics/images/index11.png" alt=""></a>
            </div>


        </div>
        @include('layouts.footerLayout')
    </div>
@endsection
