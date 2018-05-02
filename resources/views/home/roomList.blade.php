@extends('layouts.app')

@section('style')
    <style>
        .room-container {
            font-size: 0;
        }

        .room-container .room {
            background-color: #EEEEEE;
            margin-top: 0.3rem;
            padding: 0.5rem 0.35rem;
            font-size: 1rem;
            position: relative;
        }
        .room .name{
            margin-bottom: 0.3rem;
        }
        .room .je{
            font-size: 0.8rem;
        }
        .room .rp{
            position: absolute;
            bottom: 0.35rem;
            right: 0.8rem;
            font-size: 0.6rem;
        }
    </style>
@endsection
@section('content')
    <div class="fixed-header header">
        <h1>房间列表1</h1>
        <i class="iconfont icon-back" onclick="history.go(-1)"></i>
        <i class="iconfont icon-account right can_jump" _url="/user"></i>
    </div>
    <div class="content" style="      height: 100vh;">
        <div class="room-container">
            @foreach($rooms as $room)
                <div class="room can_jump" _url="/roomPage?id={{$room->id}}">
                    <div class="name">{{$room->name}}</div>
                    <div class="je">{{$room->min_str}}-{{$room->max_str}}</div>
                    <div class="rp">倍数:{{$room->peilv}}人数:{{$room->people}}</div>
                </div>
            @endforeach
        </div>
    </div>

@endsection

@section('script')
    <script>
        $(function () {

        })

    </script>
@endsection
