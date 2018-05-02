@extends('layouts.app')

@section('style')
    <style>


    </style>
@endsection
@section('content')
    <div class="fixed-header header">
        <h1>上分</h1>
        <i class="iconfont icon-back" onclick="history.go(-1)"></i>
    </div>
    <div class="content">
        <div class="content htab-box">
            <div class="htab-item">
                <div class="htab-title">支付宝
                    <i class="iconfont icon-more"></i>
                </div>
                <div class="htab-content">
                    <img src="{{$zfb_url}}">
                </div>
            </div>
            <div class="htab-item">
                <div class="htab-title">微信
                    <i class="iconfont icon-more"></i>
                </div>
                <div class="htab-content">
                    <img src="{{$wx_url}}">
                </div>
            </div>
        </div>
        <div class="form-box">
            <form action="/up" method="post">
                {{csrf_field()}}
                <div class="input-group">
                    <div class="input-label">付款方式</div>
                    <div class="input-content">
                        <select name="pay_way">
                            <option value="alipay">支付宝</option>
                            <option value="wxpay">微信</option>
                        </select>
                    </div>
                </div>
                <div class="input-group">
                    <div class="input-label">金额</div>
                    <div class="input-content">
                        <input name="money" type="text">
                    </div>
                </div>
                <div class="input-group">
                    <div class="btn submit">提交</div>
                </div>
            </form>
        </div>
    </div>
@endsection


@section('script')
    <script>
        $(function () {
            $('.submit').click(function () {
                var _this = $(this)
                _this.closest('form').ajaxSubmit(function (data) {
                    if (data.errCode == 0) {
                        _this.closest('form')[0].reset()
                        alert(data.errMsg)
                    } else {
                        alert(data.errMsg)
                    }
                })
            })
        })

    </script>
@endsection
