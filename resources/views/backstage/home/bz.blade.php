@extends('backstage.layouts.body')
@section('title', '列表')
@section('content')
    <style>
        .chou{
            margin: 12px;
            font-size: 16px;
            background-color: #ddd;
            padding: 8px;
        }
    </style>
    <div class="nav-search" id="nav-search">

    </div>
    <div class="page-content">
        <div class="page-header">
            <h1>豹子领取记录
                <small>
                    <i class="icon-double-angle-right"></i>
                </small>
            </h1>
        </div>
        <div class="row">
            <div style="padding: 10px 15px;">
                <form id="search_form" action="" method="get" enctype="multipart/form-data" class="form-search">
                    时间:
                    <span class="input-icon">
                    <input type="text" placeholder="如:20180101" value="{{Request::get('day','')}}" class="nav-search-input"
                           id="" autocomplete="off" name="day"/>
                    <i class="icon-search nav-search-icon"></i>
                    </span>
                    <button type="submit">搜索</button>
                </form>
            </div>
            <div class="chou">奖励总额：{{$sum}}元</div>
            <div class="col-xs-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover list">
                        <thead>
                        <tr>
                            <th>
                                <div>用户Id</div>
                            </th>
                            <th>
                                <div>昵称</div>
                            </th>
                            <th>
                                <div>抢到金额</div>
                            </th>
                            <th>
                                <div>是否领取</div>
                            </th>
                            <th>
                                <div>时间</div>
                            </th>
                            <th>
                                <div>操作</div>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($list as $item)
                            <tr data-item-id="{{$item->id}}">
                                <td>
                                    <div>{{$item->user?$item->user->id:''}}</div>
                                </td>
                                <td>
                                    <div>{{$item->user?$item->user->nickname:''}}</div>
                                </td>
                                <td>
                                    <div>{{$item->money_str}}</div>
                                </td>
                                <td>
                                    <div>{{$item->get==1?"已领取":"未领取"}}</div>
                                </td>
                                <td>
                                    <div>{{$item->created_at}}</div>
                                </td>
                                <td width="200">
                                    <div class="action-buttons">
                                        <a class="green faBtn" href="javascript:void(0)">发放奖励</a>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="no-record">没有记录！</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">{{$list->render()}}</div>
        </div>
    </div>
@stop
@section('script')
    <script>
        var url = '/backstage/bz';

        $(function () {
            $(".faBtn").click(function () {
                var id = $(this).closest("tr").data("item-id");
                ajaxConfirm("{{url('/backstage/fabz')}}?id=" + id, {}, "POST", function (data) {
                    location.reload();
                }, "确认发放奖励");
            });
        })
    </script>
@stop