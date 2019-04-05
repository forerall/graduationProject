@extends('layouts.appLayout')

@section('style')
    <link href="/css/style.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/css/swiper.min.css">
    <script src="/js/swiper.min.js"></script>
    <style>
        .tabs1{
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 50px;
            text-align: center;
            font-size: 0;
            border-top: 1px solid #f8f8f8;

        }
        .tab1{
            display: inline-block;
            width: 50%;
            color: #ffffff;
            padding: 5px 0;
            opacity: 0.6;
        }
        .tab1 img{
            width: 20px;
            height: 20px;
        }
        .tab1 span{
            font-size: 14px;
            display: block;
        }
        .tab1.act{
            opacity: 1;
        }
    </style>
@endsection

@section('script')
    <script>
        var mySwiper = new Swiper('.swiper-container', {
            autoplay: true,//可选选项，自动滑动
        })
    </script>
@endsection

@section('content')
    <div class="main_wrap" style="    background: rgb(47,56,71);
    min-height: 100vh;">

        <div style="    overflow: hidden;position: relative; height:100px; width:100%; margin-top:0px;background-color:	#1A1A1F">
            <div style="overflow: hidden;position: absolute; text-align:center; width:100%;vertical-align: middle; margin-top:9px;margin-left:0%">
                <img ng-src="http://thirdwx.qlogo.cn/mmopen/vi_32/P3pymVTShbMa8ycxVibe6VC4FKJYs4OCniaVzCgdWPKpapEEDQzfJEw5KoP2fVyZiau3iaKsleC8gbWPTS049NQhKg/132"
                     style=" height:83px;border: 5px solid #333; border-radius:50%;; overflow:hidden;"
                     ui-sref="tab.account" href="#/tab/account"
                     src="{{$user->avatar}}">
            </div>
            <div style="position: absolute; text-align: left; width:100%; vertical-align: middle; margin-top:25px;; margin-left:25px; left:20;  height: 19px; top: 33px; color:#FFFFFF;"
                 class="ng-binding">ID:{{$user->id}}
            </div>
            <div style="position: absolute; text-align: left; width:35%;; vertical-align: middle; margin-top:25px; margin-left:25px; left:20;  height: 19px; color:#FFFFFF;">
                <font size="4" class="ng-binding">{{$user->nickname}}</font></div>
            <div style="position: absolute; text-align: left; width:100%; vertical-align: middle; margin-top:27px; margin-left:70%;  height: 19px; left: 3px; ">
                <img height="16" src="/img/gold.png"></div>
            <div style="position: absolute; text-align: left; width:100%; vertical-align: middle; margin-top:25px; margin-left:70%;  height: 19px; left: 22px; color:#FFFFFF;"
                 class="ng-binding">{{$user->balance_str}}
            </div>
            <div style="position: absolute; text-align: left; width:100%; vertical-align: middle; margin-top:27px; margin-left:70%;  height: 19px; left: 3px; top: 33px;">
                <img height="14" src="/img/users.png"></div>
            <div style="position: absolute; text-align: left; width:100%; vertical-align: middle; margin-top:25px; margin-left:70%;  height: 19px; left: 22px; top: 33px; color:#FFFFFF;"
                 class="ng-binding">{{$sum}}
            </div>
        </div>
        <div class="notice">
            <div ng-if="notice_lx==1" class="notice_inner">
                <ul>
                    <li style="line-height: 30px;    height: 30px;">
                        <marquee behavior="scroll" class="ng-binding">
                            {{$gg}}
                        </marquee>
                    </li>
                </ul>
            </div>

        </div>

        <div class="home-cover">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide"><img style="width: 100%;" src="/images/b1.jpg" class=""></div>
                    <div class="swiper-slide"><img style="width: 100%;" src="/images/b2.PNG" class=""></div>
		    		     <div class="swiper-slide"><img style="width: 100%;" src="/images/b4.jpg" class=""></div>
                </div>
            </div>

        </div>

        <div class="module game">
            <div class="inner">
                <div class="game_img">
                    <a href="/roomList" class="">
                        <img src="/images/saolei.PNG">
                        <div class="text">
                            <div class="inner" style="display: none;">
                                <h2 class="gaime_title">扫雷</h2>
                                <p>进入游戏</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="module game">
            <div class="inner">
                <div class="game_img">
                    <a href="" class="">
                        <img src="/images/niuniu.PNG">
                        <div class="text">
                            <div class="inner" style="display: none;">
                                <h2 class="gaime_title">接龙</h2>
                                <p>进入游戏</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="module game">
            <div class="inner">
                <div class="game_img">
                    <a href="/roomList2" class="">
                        <img src="/images/banner.PNG">
                        <div class="text">
                            <div class="inner" style="display: none;">
                                <h2 class="gaime_title">接龙</h2>
                                <p>进入游戏</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
<br>
<br>

        <div class="tabs1" style="background-color: rgba(0,0,0,100)">
            <div class="tab1 act" onclick="location.href='/'">
                <img src="/img/icon/home2.png">
                <span>大厅</span>
            </div>
            <div class="tab1" style="border-left: 1px solid"  onclick="location.href='/user'">
                <img src="/img/icon/acount.png">
                <span>账户</span>
            </div>
        </div>
    </div>
@endsection



