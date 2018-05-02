@extends('layouts.app')

@section('style')
    <style>
        .user-container {
            background-color: #ffffff;
            padding: 30px 0px 10px 0px;
            border-bottom: 1px solid #dddddd;

        }

        .user-container img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
        }

        .user-container > div {
            text-align: center;
        }


    </style>
@endsection
@section('content')
    <div class="fixed-header header">
        <h1>个人中心</h1>
        <i class="iconfont icon-back" onclick="history.go(-1)"></i>
    </div>
    <div class="content">
        <div class="personal_data">
            <a href="javascript:void(0)">
                <div class="head_portrait pic_box">
                    <img src="{{$user->avatar}}" alt="">
                </div>
                <div class="data">
                    <span class="username" style="">{{$user->nickname}}</span>
                    <span class="perfect_information">Id:{{$user->id}}</span>
                    <span class="perfect_information">{{$user->balance_str}}</span>
                </div>
                <div class="enter_info">
                    <img style="display: none;" src="images/chevron-right.png">
                </div>
            </a>
        </div>

        <ul class="list">
          <li class="can_jump" _url="/">
                <a href="javascript:void(0)">
                    <img src="images/user-group.png">
                    <span>房间列表</span>
                <span class="enter">
                <img src="images/chevron-right.png">
                </span>
                </a>
            </li>
            <li class="can_jump" _url="/balanceLog">
                <a href="javascript:void(0)">
                    <img src="images/wallet.png">
                    <span>记录(今日发包:{{$user->packet}}总额:{{$user->packet_money}})</span>
                <span class="enter">
                <img src="images/chevron-right.png">
                </span>
                </a>
            </li>
            <li class="can_jump" _url="/lq">
                <a href="javascript:void(0)">
                    <img src="images/wallet.png">
                    <span>奖励领取</span>
                <span class="enter">
                <img src="images/chevron-right.png">
                </span>
                </a>
            </li>
            @if($user->proxy>0)
            <li class="can_jump" _url="/proxy">
                <a href="javascript:void(0)">
                    <img src="images/user-group.png">
                    <span>代理</span>
                <span class="enter">
                <img src="images/chevron-right.png">
                </span>
                </a>
            </li>
            <li class="can_jump" _url="/tongji">
                <a href="javascript:void(0)">
                    <img src="images/user-group.png">
                    <span>统计</span>
                <span class="enter">
                <img src="images/chevron-right.png">
                </span>
                </a>
            </li>
            @endif
            <li class="can_jump" _url="/up">
                <a href="javascript:void(0)">
                    <img src="images/weight.png">
                    <span>上分</span>
                <span class="enter">
                <img src="images/chevron-right.png">
                </span>
                </a>
            </li>
            <li class="can_jump" _url="/down">
                <a href="javascript:void(0)">
                    <img src="images/weight.png">
                    <span>下分</span>
                <span class="enter">
                <img src="images/chevron-right.png">
                </span>
                </a>
            </li>
            <li class="can_jump" _url="/gz">
                <a href="javascript:void(0)">
                    <img src="images/broadcast.png">
                    <span>规则</span>
                <span class="enter">
                <img src="images/chevron-right.png">
                </span>
                </a>
            </li>
            <li class="can_jump" _url="/kf">
                <a href="javascript:void(0)">
                    <img src="images/phone.png">
                    <span>客服</span>
                <span class="enter">
                <img src="images/chevron-right.png">
                </span>
                </a>
            </li>
        </ul>
    </div>


    <div style="margin-top: 30px;display: none">
        <form action="/logout" method="post" style="display: none;">
            {{csrf_field()}}
            <button class="btn btn-block btn-danger login-btn">退出登录</button>
        </form>
    </div>
@endsection


@section('script')
    <script>
        $(function () {

        })

    </script>
@endsection
