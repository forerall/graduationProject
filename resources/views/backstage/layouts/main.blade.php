<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="/box/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="/box/css/base.css" rel="stylesheet"/>
    <link href="/box/css/control.css" rel="stylesheet"/>
    <link href="/box/css/sweetalert.css" rel="stylesheet"/>

    <!-- ace styles -->
    <link rel="stylesheet" href="/assets/css/font-awesome.min.css"/>
    <!--[if IE 7]>
    <link rel="stylesheet" href="/assets/css/font-awesome-ie7.min.css"/>
    <![endif]-->
    <link rel="stylesheet" href="/assets/css/ace.min.css"/>
    <link rel="stylesheet" href="/assets/css/ace-rtl.min.css"/>
    <link rel="stylesheet" href="/assets/css/ace-skins.min.css"/>
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="/assets/css/ace-ie.min.css"/>
    <![endif]-->
    <style>
        .order-nav {
            padding: 0px 5px 10px 5px;
            border-bottom: 1px solid rgb(199, 218, 235);
            margin-bottom: 10px;
        }

        .order-nav > div {
            display: inline-block;
            padding: 5px 15px;
            cursor: pointer;
        }

        .order-nav .current {
            background-color: rgb(199, 218, 235);
            border-radius: 3px;
        }

        .search-container {
            padding: 10px 0;
        }
    </style>
    @yield('style')
</head>
<body>
@yield('body')

<div class="alert alert-info" style="display:none;position: fixed;left: 50%;transform: translateX(-50%);top: 20%;">
    <button type="button" class="close" style="display: none" >
        <i class="icon-remove"></i>
    </button>
    <p>有新的上分/下分待处理！
        <a id="up" href="/backstage/recharge?state=1">立即查看</a>
        <a id="down" href="/backstage/withdraw?state=1">立即查看</a>
    </p>
    <br>
</div>
<audio id="tipmusic" src="/images/back.mp3"></audio>
<script src="/box/jquery-2.0.3.min.js"></script>
<script src="/box/base.js"></script>
<script src="/box/jquery.form.js"></script>
<script src="/box/sweetalert.min.js"></script>
<script src="/box/ui.js"></script>


<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/typeahead-bs2.min.js"></script>
<!-- page specific plugin scripts -->
<!-- ace scripts -->
<script src="/assets/js/ace-extra.min.js"></script>
<script src="/assets/js/ace-elements.min.js"></script>
<script src="/assets/js/ace.min.js"></script>
<script>
    $(function () {
        ace.settings.navbar_fixed(true);
        ace.settings.sidebar_fixed(true);
        //ace.settings.main_container_fixed(false);
        //try{ace.settings.check('main-container' , 'fixed')}catch(e){}
        //ace.settings.sidebar_collapsed(true);
        try {
            ace.settings.check('sidebar', 'collapsed')
        } catch (e) {
        }

        $('.alert-info .close').click(function(){
           $(this).closest('.alert-info').hide();
        })
        if (typeof url != 'undefined') {
            if ($('.nav-list').find('a[href="' + url + '"]').length == 0)return;
            var li = $('.nav-list').find('a[href="' + url + '"]').parent();
            if (li.length > 0) {
                li.addClass('active');
                while (!li.parent().hasClass('nav')) {
                    if (li.parent().hasClass('submenu')) {
                        li.parent().show();
                    }
                    if (li.parent().is('li')) {
                        li.parent().addClass('open');
                    }
                    li = li.parent();
                }
            }
        }
        $('.alert-info').hide();
        $('.clearTip').click(function () {
            $.getJSON('/backstage/clearTips', {}, function (result) {
            });
        })
        var t = setInterval(function () {
            $.getJSON('/backstage/tips', {}, function (result) {
                if (result.data == 1) {
                    document.getElementById('tipmusic').play();
                    $('.alert-info').find('a').hide()
                    if(result.status == 1){
                        $('.alert-info').find('#up').show();
                    }else{
                        $('.alert-info').find('#down').show();
                    }
                    $('.alert-info').show()
                }
            })
        }, 10000)

    })
</script>
@yield('script')
</body>
</html>
