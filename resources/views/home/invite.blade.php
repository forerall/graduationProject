@extends('layouts.app')

@section('style')
    <style>

    </style>
@endsection
@section('content')
    <div>
        <p>邀请注册送红包</p>
        <p>邀请好友注册两人都获得红包奖励</p>
    </div>
@endsection


@section('script')
    <script type="text/javascript">
        {!! $configJs !!}
        wx.ready(function () {
            wx.onMenuShareTimeline({
                title: '{{$title}}', // 分享标题
                link: '{{$url}}', // 分享链接
                imgUrl: '{{$imgUrl}}', // 分享图标
                success: function () {
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                }
            });
            wx.onMenuShareAppMessage({
                title: '{{$title}}', // 分享标题
                desc: '{{$desc}}', // 分享描述
                link: '{{$url}}', // 分享链接
                imgUrl: '{{$imgUrl}}', // 分享图标
                type: 'link', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success: function () {
                    // 用户确认分享后执行的回调函数
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                }
            });
        });
    </script>
@endsection
