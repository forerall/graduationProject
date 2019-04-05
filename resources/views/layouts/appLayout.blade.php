@extends('layouts.baseLayout')
@section('headLayout')
    <title>{{config('app.name')}}</title>
    <link href="/statics/common/css/base.css" rel="stylesheet" type="text/css"/>
    <link href="/statics/css/footer.css" rel="stylesheet" type="text/css"/>
    <link href="/statics/font/iconfont.css" rel="stylesheet" type="text/css"/>


    @yield('style')
@endsection

@section('bodyLayout')
    <div id="app" class="">
        @yield('content')
    </div>
    <script src="/statics/common/js/jquery-2.0.3.min.js"></script>
    <script src="/statics/common/js/minirefresh.js"></script>
    <script src="/statics/common/js/template7.min.js"></script>
    <script src="/statics/common/js/base.js"></script>
    @yield('script')
@endsection