<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/app/css/common.css">
    <link rel="stylesheet" href="/app/css/minirefresh.css">
    <link rel="stylesheet" href="/app/css/footer.css">
    <link rel="stylesheet" href="/app/css/envelope.css">
    <link rel="stylesheet" href="/static/css/font-awesome.min.css">
    <link rel="stylesheet" href="/static/font/iconfont.css">
</head>
<body>
<style>

    .tab-3 {
        font-size: 0;
    }

    .tab-3 > div {
        width: 33.3%;
        display: inline-block;
        vertical-align: top;
        font-size: 0.65rem;
        text-align: center;
    }
    .enve-top{
        background: #292929;
        padding: 1.1rem 0;
    }
    .coins{
        color: #7B7B7B;
        padding: 0.4rem;
        font-size: 0.7rem;
        margin: 0.2rem 0;
    }
    .treasure{
        color: white;
        font-size: 2.1rem !important;
        padding: 0.2rem 1.6rem;
    }

    .standby{
        color: #7B7B7B;
        padding: 0.4rem;
        font-size: 0.7rem;
        margin: 0.2rem 0;
    }

</style>

<div class="enve-top tab-3">
    <div>
        <div class="coins">已抢金币:0.00</div>
        <div class="coins">已抢金币:0.00</div>
        <div class="coins">已抢金币:0.00</div>
    </div>
    <div class="treasure">
        宝箱
    </div>
    <div>

        <div class="standby">备用金币:0.00</div>
        <div class="standby">备用金币:0.00</div>
        <div class="standby">备用金币:0.00</div>

    </div>
</div>

<div class="enve">

</div>


{{--页脚导航（公共）--}}
<footer id="footer-nav">
    <div class="weui-flex">
        <div class="weui-flex__item f-tac active">
            <a href="javascript:void(0);">
                <i class="iconfont icon-home"></i>
                <div class="ftext">首页</div>
            </a>
        </div>
        <div class="weui-flex__item f-tac">
            <a href="javascript:void(0);">
                <i class="iconfont "></i>
                <div class="ftext">无尽</div>
            </a>
        </div>
        <div class="weui-flex__item f-tac">
            <a href="javascript:void(0);">
                <i class="iconfont "></i>
                <div class="ftext">扫雷</div>
            </a>
        </div>
        <div class="weui-flex__item f-tac">
            <a href="javascript:void(0);">
                <i class="iconfont"></i>
                <div class="ftext">我的</div>
            </a>
        </div>
    </div>
</footer>

<script src="/app/js/jquery-2.0.3.min.js"></script>
<script src="/app/js/minirefresh.js"></script>
<script src="/app/js/template7.js"></script>
<script src="/app/js/base.js"></script>
<script>
    var app = new App(function () {

    })
</script>
</body>
</html>