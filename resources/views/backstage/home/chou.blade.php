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
            <h1>抽水记录
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
            <div class="chou">抽水总额：{{$sum}}元</div>
            <div class="col-xs-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover list">
                        <thead>
                        <tr>
                            <th>
                                <div>记录ID</div>
                            </th>
                            <th>
                                <div>房间</div>
                            </th>
                            <th>
                                <div>用户</div>
                            </th>
                            <th>
                                <div>红包金额</div>
                            </th>
                            <th>
                                <div>抽水</div>
                            </th>
                            <th>
                                <div>时间</div>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($list as $item)
                            <tr data-item-id="{{$item->id}}">
                                <td>
                                    <div>{{$item->id}}</div>
                                </td>
                                <td>
                                    <div>{{$item->room?$item->room->name:''}}</div>
                                </td>
                                <td>
                                    <div>{{$item->user?$item->user->nickname:\App\Services\GameService::systemUser()->nickname}}</div>
                                </td>
                                <td>
                                    <div>{{$item->packet_money_str}}</div>
                                </td>
                                <td>
                                    <div>{{$item->chou/100}}</div>
                                </td>
                                <td>
                                    <div>{{$item->created_at}}</div>
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
        var url = '/backstage/chou';

        $(function () {
            $(".openBtn").click(function () {
                var id = $(this).closest("tr").data("item-id");
                var url = '/backstage/openProxy';
                ajaxConfirm(url,{uid:id},'POST',function(){
                    location.reload();
                },'确认操作？','');
            });
            $(".closeBtn").click(function () {
                var id = $(this).closest("tr").data("item-id");
                var url = '/backstage/closeProxy';
                ajaxConfirm(url,{uid:id},'POST',function(){
                    location.reload();
                },'确认操作？','');

            });
        })
    </script>
@stop