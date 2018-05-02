@extends('layouts.app')

@section('style')
    <style>
        .kf{
            padding: 2rem 3% 1rem 3%;
            text-align: center;
        }
        .kf img{
            width: 70%;
        }
        .kf p{
            color: #aaaaaa;
            margin-top: 0.3rem;
            font-size: 0.6rem;
        }
    </style>
@endsection
@section('content')
    <div class="fixed-header header">
        <h1>客服</h1>
        <i class="iconfont icon-back" onclick="history.go(-1)"></i>
    </div>
    <div class="content">
        <div class="kf">
            <img src="{{$config->value}}">
            <p>扫描二维码添加客服</p>
        </div>

    </div>

@endsection


@section('script')
    <script type="text/javascript">
        $(function () {

        })
    </script>
@endsection
