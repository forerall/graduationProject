@extends('layouts.app')

@section('style')
    <style>
        .room-container {
            font-size: 0;
        }

        .room-container .room {
            margin-bottom: 0.85rem;
            position: relative;
            display: inline-block;
            width: 30%;
            margin-left: 3%;
        }

        .col_inner {
            text-align: center;
            font-size: 0.6rem;
            color: #fff;
        }

        .col_inner img {
            width: 80px;

        }

        .room .name {
            margin-bottom: 0.3rem;
            font-size: 0.8rem;
        }

        .room .je {
            font-size: 0.6rem;
        }

        .room .rp {
            bottom: 0.35rem;
            right: 0.8rem;
            font-size: 0.6rem;
        }

        .left-btn {
            display: block;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            color: #fff;
            text-align: center;
            line-height: 60px;
            font-size: 17px;
            margin-bottom: 10px;
            background-color: #0090FF;
        }

        .left-bar {
            position: absolute;
            top: 0;
            left: 2%;
        }

        .top-btn {
            margin: 10px;
            width: 100px;
            height: 40px;
            text-align: center;
            background-color: rebeccapurple;
            line-height: 40px;
            border-radius: 4px;
        }
        .btn{
            background-color: rebeccapurple;
            border-radius: 4px;
            width: 100px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            font-size: 16px;
            margin:  0 auto;
        }
        .tt{
            line-height: 40px;
            border-bottom: 1px solid #ddd;
            font-size: 20px;
            text-align: center;
            font-weight: bold;
        }
    </style>
@endsection
@section('content')
    <div style="background:black;">

        <table style="width: 100%;color: #fff;font-size: 0.8rem;display: none">
            <tr>
                <td>
                    <img style="border: 4px solid #333;margin: 15px 0 15px 15px;width: 60px;height: 60px;border-radius: 50%;"
                         src="{{$user->avatar}}"></td>
                <td>
                    <div>
                        <p>ID：{{$user->id}}</p>
                        <p>昵称：{{$user->nickname}}</p>
                        <p>余额：{{$user->balance}}</p>
                    </div>
                </td>
                <td>
                    <div>
                        在线人数：{{$sum}}
                    </div>
                </td>
            </tr>
        </table>
        <div class="fixed-header header">
            <h1>房间列表</h1>
            <i class="iconfont icon-back can_jump" _url="/"></i>
            <i class="iconfont icon-account right can_jump" _url="/user"></i>
        </div>
       <div class="content" style="height: 100vh">
           <div style="display: none">
               <img src="/images/game_G04.png" style="width: 96%;margin-left: 2%;">
           </div>

           <div class="content" style="position: relative;margin-top: 20px;">

               <div class="room-container">
                   @foreach($rooms as $room)
                       <div class="room" room_id="{{$room->id}}">
                           <div class="col_inner">
                               <img src="/images/game_icon_G04_2.png">
                               <p>{{$room->name}}</p>
                               <p class="ng-binding">{{$room->min_str}}-{{$room->max_str}}金</p>
                               <p class="ng-binding">当前在线:{{$room->people}}人</p>
                           </div>
                       </div>
                   @endforeach
               </div>
           </div>
           <div class="password-box" style="position: absolute;top:0;width: 100%;height: 100%;background-color: rgba(0,0,0,0.5);display: none">
                <div style="position: absolute;top: 150px;background-color: #fff;width: 60%;margin-left: 20%;padding: 10px">
                    <div class="tt">请输入房间密码</div>
                    <div style="margin-top: 20px">
                        <input type="password" id="pass" style="height: 30px;line-height: 30px;width: 100%">
                    </div>
                    <div style="margin-top: 20px">
                        <div class="btn go">进入房间</div>
                    </div>
                    <div style="margin-top: 20px">
                        <div class="btn close">关闭</div>
                    </div>
                </div>
           </div>
       </div>
    </div>
@endsection

@section('script')
    <script>
        var room_id = 0;
        $(function () {
            $('.room').click(function(){
                room_id = $(this).attr('room_id');
                $('.password-box').show();
            })
        })
        $('.close').click(function(){
            $('.password-box').hide();
        })
        $('.go').click(function(){
            if(room_id>0){
                $.getJSON('/pass',{id:room_id,pass:$('#pass').val()},function(result){
                    if(result.errCode==0){
                        location.href = '/roomPage?id='+room_id;
                    }else{
                        alert(result.errMsg)
                    }
                })
            }
            $('.password-box').hide();
        })

    </script>
@endsection
