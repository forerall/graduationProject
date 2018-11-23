@extends('backstage.layouts.body')
@section('title', '列表')
@section('content')
    <style>
        .up-btn,.down-btn{
            padding: 2px 8px;
            color: #ffffff;
            background-color: blue;
            cursor: pointer;
        }
        .chou{
            margin: 12px;
            font-size: 16px;
            background-color: #ddd;
            padding: 8px;
        }
    </style>
    <div class="nav-search" id="nav-search">
        <form id="search_form" action="" method="get" enctype="multipart/form-data" class="form-search">
            <input type="hidden" name="store" value="{{request('store','')}}">
            <span class="input-icon">
                <input type="text" placeholder="搜索..." value="{{Request::get('keyword','')}}" class="nav-search-input"
                       id="" autocomplete="off" name="keyword"/>
                <i class="icon-search nav-search-icon"></i>
                </span>
        </form>
    </div>
    <div class="page-content">
        <div class="page-header">
            <h1>用户列表
                <small>
                    <i class="icon-double-angle-right"></i>
                </small>
            </h1>
        </div>
        <div class="row">
            @if(request('type')>0)
                <div class="col-xs-12 r-toolbar">
                    <a href="/backstage/user/create?type=1" class="btn btn-sm">添加机器人</a>
                </div>
            @else
            <div class="chou">用户总余额：{{$sum}}元</div>
            @endif
            <div class="col-xs-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover list">
                        <thead>
                        <tr>
                            <th>
                                <div>ID</div>
                            </th>
                            <th>
                                <div>名称</div>
                            </th>
                            @if(request('type')>0)
                            <th>
                                <div>房间</div>
                            </th>
                            @endif
                            <th>
                                <div>胜率</div>
                            </th>
                            <th>
                                <div>余额</div>

                            </th> <th>
                                <div>发包数量</div>

                            </th> <th>
                                <div>发包金额</div>

                            </th>
                            <th>
                                <div>代理</div>
                            </th>
                            <th>
                                <div>注册时间</div>
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
                                    <div>{{$item->id}}</div>
                                </td>
                                <td>
                                    <div>{{$item->nickname}}</div>
                                </td>
                                @if($item->type==1)
                                <td>
                                    <div>{{$item->room?$item->room->name:''}}</div>
                                </td>
                                @endif
                                <td>
                                    <div>{{$item->rate}}</div>
                                </td>
                                <td>
                                    <div>{{$item->balance_str}}</div>
                                    <div>
                                        <input type="text"><span class="up-btn">上分</span>
                                        <span class="down-btn">下分</span>
                                    </div>
                                </td>
                                <td>
                                    <div>{{$item->packet}}</div>
                                </td><td>
                                    <div>{{$item->packet_money}}</div>
                                </td><td>
                                    <div>{{$item->proxy_str}}</div>
                                </td>
                                <td>
                                    <div>{{$item->created_at}}</div>
                                </td>
                                <td width="180">
                                    <div class="  action-buttons">
                                        <a class="blue showBtn" href="/backstage/user/{{$item->id}}{{request('type')==1?'?type=1':''}}"><i
                                                    class="icon-zoom-in bigger-130"></i></a>
                                        <a class="green editBtn" href="/backstage/user/{{$item->id}}/edit{{request('type')==1?'?type=1':''}}"><i
                                                    class="icon-pencil bigger-130"></i></a>
                                        
                                        <a class="red jyBtn" href="javascript:void(0)">
                                            @if($item->state == 0)
                                                禁用
                                                @else
                                            开启
                                                @endif
                                        </a>
                                        @if($item->type==0)
                                        @if($item->proxy==0)
                                        <a class="green openBtn" href="javascript:void(0)">开启代理</a>
                                        @else
                                        <a class="green closeBtn" href="javascript:void(0)">关闭代理</a>
                                        @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="no-record">没有记录！</td>
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
        @if(request('type')>0)
        var url = '/backstage/user?type=1';
                @else
        var url = '/backstage/user';
                @endif



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

            $(".up-btn").click(function () {
                var id = $(this).closest("tr").data("item-id");
                var url = '/backstage/userup';
                var fen = $(this).parent().find('input').val();
                ajaxConfirm(url,{uid:id,fen:fen},'POST',function(){
                    location.reload();
                },'确认操作？','');

            });
            $(".down-btn").click(function () {
                var id = $(this).closest("tr").data("item-id");
                var url = '/backstage/userdown';
                var fen = $(this).parent().find('input').val();
                ajaxConfirm(url,{uid:id,fen:fen},'POST',function(){
                    location.reload();
                },'确认操作？','');

            });
            $(".jyBtn").click(function () {
                var id = $(this).closest("tr").data("item-id");
                ajaxConfirm("{{url('/backstage/jingyong')}}/" + id, {}, "POST", function (data) {
                    location.reload();
                }, "确认");
            });
            $(".delBtn").click(function () {
                var id = $(this).closest("tr").data("item-id");
                ajaxConfirm("{{url('/backstage/user')}}/" + id, {}, "DELETE", function (data) {
                    location.reload();
                }, "确认删除");
            });
        })
    </script>
@stop