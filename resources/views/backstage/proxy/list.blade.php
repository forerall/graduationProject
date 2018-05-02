@extends('backstage.layouts.body')
@section('title', '列表')
@section('content')
    <div class="nav-search" id="nav-search">

    </div>
    <div class="page-content">
        <div class="page-header">
            <h1>
                @if(request('pid')>0)
                    线下列表
                @else
                    代理列表
                @endif
                <small>
                    <i class="icon-double-angle-right"></i>
                </small>
            </h1>
        </div>
        <div class="row">
            @if(request('pid')!== null)
                <div style="padding: 10px 15px;">
                <form id="search_form" action="" method="get" enctype="multipart/form-data" class="form-search">
                    代理用户Id:
                    <span class="input-icon">
                    <input type="text" placeholder="搜索..." value="{{Request::get('pid','')}}" class="nav-search-input"
                           id="" autocomplete="off" name="pid"/>
                    <i class="icon-search nav-search-icon"></i>
                    </span>
                    <button type="submit">搜索</button>
                </form>
                </div>
            @endif
            <div class="col-xs-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover list">
                        <thead>
                        <tr>
                            <th>
                                <div>用户ID</div>
                            </th>
                            <th>
                                <div>名称</div>
                            </th>
                            <th>
                                <div>胜率</div>
                            </th>
                            <th>
                                <div>余额</div>
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
                                <td>
                                    <div>{{$item->rate}}</div>
                                </td>
                                <td>
                                    <div>{{$item->balance_str}}</div>
                                </td>
                                <td>
                                    <div>{{$item->proxy_str}}</div>
                                </td>
                                <td>
                                    <div>{{$item->created_at}}</div>
                                </td>
                                <td width="180">
                                    <div class="  action-buttons">
                                        @if($item->proxy==0)
                                            <a class="green openBtn" href="javascript:void(0)">开启代理</a>
                                        @else
                                            <a class="green closeBtn" href="javascript:void(0)">关闭代理</a>
                                        @endif
                                        <a class="green" href="/backstage/proxyList?pid={{$item->id}}">下线</a>
                                        <a class="green" href="/backstage/tongji?uid={{$item->id}}">统计</a>
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
        @if(request('pid')!==null)
        var url = '/backstage/proxyList?pid=0';
        @else
        var url = '/backstage/proxyList';
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
        })
    </script>
@stop