@extends('layouts.app')

@section('style')
    <style>


    </style>
@endsection
@section('content')
    <div class="fixed-header header">
        <h1>游戏规则</h1>
        <i class="iconfont icon-back" onclick="history.go(-1)"></i>
    </div>
    <div class="content">
        <p style="white-space: pre-line;padding: 0.5rem 0.8rem;font-size: 0.8rem;color: #aaaaaa">

            可发可抢扫雷
            包数为 :5-10包
            倍数为: 5包2倍，6包1.7倍
            7包1.5倍，8包1.3倍
            9包1.2倍，10包1.1倍
            雷数为: 0-9任意数字
            发包者设置雷数，抢包者抢到
            的金额最后一位数字与雷的数字相同，即为中雷!
            不相同，即为不中雷!
            发包后自动扣除发包相对应的金币
            有人中雷会自动返还相应赔率的金币
            不中雷会自动增加所抢的金币
            抢包中雷会自动扣除相对应赔率的金币
            如果红包没人枪十分中内自动退回
            本平台保持对玩家公正公平系统研发。
        </p>
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
