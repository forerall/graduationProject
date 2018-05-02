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
            <h1>用户流水
                <small>
                    <i class="icon-double-angle-right"></i>
                </small>
            </h1>
        </div>
        <div class="row">
            <div style="padding: 10px 15px;">
                <form id="search_form" action="" method="get" enctype="multipart/form-data" class="form-search">
                    用户Id:
                    <span class="input-icon">
                    <input type="text" placeholder="" value="{{Request::get('uid','')}}" class="nav-search-input"
                           id="" autocomplete="off" name="uid"/>
                    <i class="icon-search nav-search-icon"></i>
                    </span>
                    <button type="submit">搜索</button>
                </form>
            </div>
            <div class="chou" >
                <span>充值总额：{{$recharge}}元</span>
                <span style="display:none;">消费总额：{{$consume}}元</span>
                <span>余额：{{$balance}}元</span>
            </div>
            <div class="col-xs-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover list">
                        <thead>
                        <tr>
                            <th>
                                <div>记录ID</div>
                            </th>
                            <th>
                                <div>用户ID</div>
                            </th>
                            <th>
                                <div>昵称</div>
                            </th>
                            <th>
                                <div>类型</div>
                            </th>
                            <th>
                                <div>金额</div>
                            </th>
                            <th>
                                <div>余额</div>
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
                                    <div>{{$item->user?$item->user->id:''}}</div>
                                </td>
                                <td>
                                    <div>{{$item->user?$item->user->nickname:''}}</div>
                                </td>
                                <td>
                                    <div>{{$item->detail}}</div>
                                </td>
                                <td>
                                    <div>{{$item->money_str}}</div>
                                </td>
                                <td>
                                    <div>{{$item->balance_str}}</div>
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
        var url = '/backstage/balance';

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