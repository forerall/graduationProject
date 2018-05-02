@extends('layouts.app')

@section('style')
    <style>
        .qrcode{
            margin: 0.5rem 0;
        }
        .qrcode img{
            width: 70%;
            margin-left: 15%;
        }

    </style>
@endsection
@section('content')
    <div>
        <div class="fixed-header header">
            <h1>代理列表</h1>
            <i class="iconfont icon-back" onclick="history.go(-1)"></i>
        </div>
        <div class="content">
            <div class="qrcode">
                <img src="http://qr.liantu.com/api.php?text={{urlencode($qrCodeUrl)}}">
            </div>
            <div class="table-list">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>头像</th>
                        <th>用户名</th>
                        @if($user->proxy>0&&$user->proxy<3)
                        <th>开放代理</th>
                        @endif
                    </tr>
                    @forelse($list as $u)
                        <tr data-item-id="{{$u->id}}">
                            <td>{{$u->id}}</td>
                            <td><img src="{{$u->avatar}}"></td>
                            <td>{{$u->nickname}}</td>
                            @if($user->proxy>0&&$user->proxy<3)
                            <td>
                                @if($u->proxy>0&&$u->proxy<3)
                                    <span>已开放</span>
                                    @else
                                    <span class="open-proxy">立即开放</span>
                                @endif
                            </td>
                            @endif
                        </tr>
                        @empty
                            <tr>
                                <td class="record-empty" colspan="4">没有记录</td>
                            </tr>
                    @endforelse
                </table>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script type="text/javascript">
        $(function(){
            $('.open-proxy').click(function(){
                var id = $(this).closest('tr').data('item-id')
                $.getJSON('/openProxy',{uid:id},function(result){
                    alert(result.errMsg);
                    location.reload()
                })
            })
        })
    </script>
@endsection
