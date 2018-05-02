<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <link href="/resources/css/base.css" rel="stylesheet"/>
    <style>
        .article-item{
            padding: 5px 0px;
            border-bottom: 1px dashed #dddddd;
        }
        .container{
            margin: 8px 8px 18px 8px;
            padding: 13px;
            border: 1px solid #eaeaea;
        }
    </style>
    @yield('style')
</head>

<!--  页面头部【end】 -->
<body class="body1" style="">
<div id="getValues" value="prdlist" style="display:none;"></div>
<div id="top">
    <div id="header">
        <div id="logo">
            <div id="MODBLK_227" class="mod_block media_image mb_logo_block">
                <div class="flash_image" style="padding-top: 10px">
                    <img src="/resources/images/logo1.png" alt="{{ config('app.name') }}" style="height: 40px">
                    <h2 style="font-size: 20px;display: inline-block;line-height: 40px;vertical-align: top;">{{ config('app.name') }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div id="nav">
        <div id="MODBLK_169" class="mod_block mb_kOzgrVaw_block">
            <div class="nav_l"></div>
            <ul id="nav_EIJgtU" class="navigation">
                <li>
                    <a href="/" target="_self">
                        首页</a>
                </li>
                <li>
                    <a href="/product" target="_self">
                        产品介绍</a>
                </li>
                <li>
                    <a href="/article" target="_self">
                        热点资讯</a>
                </li>
                <li>
                    <a href="/anli" target="_self">
                        案例展示</a>
                </li>
                <li>
                    <a href="/zx" target="_self">
                        招贤纳士</a>
                </li>
                <li>
                    <a href="/liuyan" target="_self">
                        用户留言</a>
                </li>
                <li>
                    <a href="/download" target="_self">
                        APP下载</a>
                </li>
                <li>
                    <a href="/contact" target="_self">
                        联系我们</a>
                </li>
            </ul>
            <div class="nav_r"></div>
        </div>
    </div>
</div>
<div id="main_div">
    <div id="main_con">
        <div class="web_bg">
            <div id="banner">
                <div id="MODBLK_239" class="mod_block media_image mb_banner_block">
                    <div class="flash_image">

                        <img src="/resources/images/02164.jpg" alt="" width="990" height="300">
                    </div>
                </div>
            </div>
            @yield('content')

        </div>
        <!--main_div end-->
        <div id="footer_bg">
            <!-- 页脚【start】 -->
            <div id="footer">
                <div class="copyright">
                    <style type="text/css">
                        .mb_foot_block {
                            position: relative;
                        }

                        .mb_foot_block a {
                            display: inline;
                        }
                    </style>
                    <div id="MODBLK_170" class="mod_block mb_foot_block">
                        <style type="text/css">
                            .copyright .list_bot {
                                display: none;
                            }
                        </style>
                        <div class="com_con">Copyright © 2017-2020,All rights reserved<br>
                            版权所有 © 厦门市石盒子网络科技有限公司<br>
                            闽ICP备16032181号-1
                        </div>
                        <div class="list_bot"></div>
                    </div>
                    <div id="MODBLK_1235" class="mod_block mb_foot_block">
                        <h3 class="blk_t"></h3>
                        <style type="text/css">
                            .copyright .list_bot {
                                display: none;
                            }
                        </style>
                        <div class="com_con"></div>
                        <div class="list_bot"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="display:none;" class="jqPreload0"></div>
</div>

<script src="/box/jquery-2.0.3.min.js"></script>
<script>
    $(function(){
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') }
        });
        $('.mess_submit').find('input').click(function(e){
            e.preventDefault();
            var data = {};
            $(this).closest('#mess_main').find('input,textarea').each(function(){
                data[$(this).attr('name')] = $(this).val();
            })
            $.ajax({
                url:'/api/feedback',
                data:{
                    content:data['mess[message]'],
                    email:data['mess[email]'],
                    username:data['mess[username]'],
                    tel:data['mess[tele]'],
                },
                dataType:'json',
                type:'POST',
                success:function(data,status,xhr){
                    if(data.errCode==2){
                        for(i in data.data.error){
                            alert(data.data.error[i]);return false;
                        }
                    }else{
                        alert(data.errMsg);
                    }

                },
                error: function (xhr, status, error) {
                    alert(data.errMsg);
                    //ajaxProcess(xhr.status,xhr.responseText);
                    console.log('ajax error:'+xhr.status);
                }
            });
        })
    })
</script>
@yield('script')
</body>
</html>