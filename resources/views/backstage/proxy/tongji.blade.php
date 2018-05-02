@extends('backstage.layouts.body')
@section('title', '列表')
@section('content')

    <div class="page-content">
        <div class="page-header">
            <h1>代理统计
                <small>
                    <i class="icon-double-angle-right"></i>
                </small>
            </h1>
        </div>
        <div class="row">

                <div style="padding: 10px 15px;">
                    <form id="search_form" action="" method="get" enctype="multipart/form-data" class="form-search">
                        日期： <input type="text" placeholder="如20180101" value="{{Request::get('day','')}}" class="nav-search-input"
                                   id="" autocomplete="off" name="day"/>
                        用户Id:
                    <span class="input-icon">
                    <input type="text" placeholder="搜索..." value="{{Request::get('uid','')}}" class="nav-search-input"
                           id="" autocomplete="off" name="uid"/>

                    <i class="icon-search nav-search-icon"></i>
                    </span>
                        <button type="submit">搜索</button>
                    </form>
                </div>

            <div class="col-xs-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover list">
                        <tr>

                            <th>用户ID</th>
                            <th>用户名</th>
                            <th>余额</th>
                            <th>上分</th>
                            <th>下分</th>
                        </tr>
                        @forelse($list as $u)
                            <tr data-item-id="{{$u->id}}">
                                <td>{{$u->id}}</td>

                                <td>{{$u->nickname}}</td>
                                <td>{{$u->balance_str}}</td>
                                <td>{{$u->up}}</td>
                                <td>{{$u->down}}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="no-record" colspan="5">没有记录</td>
                            </tr>
                        @endforelse
                    </table>
                </div>
            </div>
        </div>

    </div>
@stop
@section('script')
    <script>
        var url = '/backstage/tongji?uid=';

        $(function () {
            $(".openBtn").click(function () {
                var id = $(this).closest("tr").data("item-id");
                var url = '/backstage/openProxy';
                ajaxConfirm(url, {uid: id}, 'POST', function () {
                    location.reload();
                }, '确认操作？', '');
            });
            $(".closeBtn").click(function () {
                var id = $(this).closest("tr").data("item-id");
                var url = '/backstage/closeProxy';
                ajaxConfirm(url, {uid: id}, 'POST', function () {
                    location.reload();
                }, '确认操作？', '');

            });
        })
    </script>
@stop