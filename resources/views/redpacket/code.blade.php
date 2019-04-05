@extends('layouts.appLayout')
@section('style')
    <style>
        body {
            background: #eee;
        }
    </style>
@endsection

@section('script')
    <script>
        var app = new App(function () {

        })
    </script>
@endsection

@section('content')
    <div class="header">
        <div>我的邀请码</div>
    </div>
    <div class="body-container">
        <div style="padding: 3rem 0;text-align: center">邀请码：{{$user->recom_code}}</div>
        <br>
        <br>
        <div class="btn">复制邀请码</div>
    </div>

@endsection
