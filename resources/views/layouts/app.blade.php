<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{config('app.name')}}</title>
    <link href="/css/common.css?r=34" rel="stylesheet" type="text/css"/>
    <link href="/fonts/al/iconfont.css" rel="stylesheet" type="text/css"/>
    @yield('style')
</head>
<body>
<div class="body-container">
    @yield('content')
</div>


<script src="/box/jquery-2.0.3.min.js"></script>
<script src="/box/base.js"></script>
<script src="/box/jquery.form.js"></script>
<script src="/box/sweetalert.min.js"></script>
<script src="/js/commonTools.js?g=3"></script>
<script src="/js/template-web.js"></script>
<script src="/js/iscroll.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    (function (doc, win) {
        var docEl = doc.documentElement,
                resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
                recalc = function () {
                    var clientWidth = docEl.clientWidth;
                    if (!clientWidth) return;
                    docEl.style.fontSize = 20 * (clientWidth / 375) + 'px';
                };

        if (!doc.addEventListener) return;
        recalc();
        win.addEventListener(resizeEvt, recalc, false);
        doc.addEventListener('DOMContentLoaded', recalc, false);
    })(document, window);
</script>
<script>
    function checkLogin() {
        @if(!\Illuminate\Support\Facades\Auth::check())
                location.href = '/login';
        return false
        @else
                return true;
        @endif
    }
</script>
@yield('script')
</body>
</html>
