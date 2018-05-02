@extends('layouts.app')

@section('style')
    <style>

    </style>
@endsection
@section('content')
    <div>
        <div class="fixed-header header">
            <h1>统计列表</h1>
            <i class="iconfont icon-back" onclick="history.go(-1)"></i>
        </div>
        <div class="content">
            <div style="padding: 1rem 0.35rem;background-color: #FFFFFF">时间：{{$day_str}}</div>
            <div class="table-list">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>头像</th>
                        <th>用户名</th>
                        <th>余额</th>
                        <th>上分</th>
                        <th>下分</th>
                        <th>发包数量</th>
                        <th>发包金额</th>
                    </tr>
                    @forelse($list as $u)
                        <tr data-item-id="{{$u->id}}">
                            <td>{{$u->id}}</td>
                            <td><img src="{{$u->avatar}}"></td>
                            <td>{{$u->nickname}}</td>
                            <td>{{$u->balance_str}}</td>
                            <td>{{$u->up}}</td>
                            <td>{{$u->down}}</td>
                            <td>{{$u->packet}}</td>
                            <td>{{$u->packet_money}}</td>
                        </tr>
                        @empty
                            <tr>
                                <td class="record-empty" colspan="7">没有记录</td>
                            </tr>
                    @endforelse
                </table>
            </div>
            <div class="page-tool">
                <span class="can_jump" _url="{{$former}}">前一天</span>
                @if($next)
                    <span class="can_jump" _url="{{$next}}">后一天</span>
                @endif
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script type="text/javascript">
        $(function(){
            $('.open-proxy').click(function(){
                var id = $(this).closest('tr').data('item-id')

            })
        })
    </script>
@endsection
