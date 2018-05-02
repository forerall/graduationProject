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
                       <div class="room can_jump" _url="/roomPage?id={{$room->id}}">
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
       </div>
    </div>
@endsection

@section('script')
    <script>
        $(function () {

        })

    </script>
@endsection
