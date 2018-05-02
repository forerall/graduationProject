@extends('backstage.layouts.body')
@section('title', '列表')
@section('content')
    <div class="nav-search" id="nav-search">
        <form id="search_form" action="" method="get" enctype="multipart/form-data" class="form-search">
            <input type="hidden" name="state" value="{{request('state')}}">
            <span class="input-icon">
                <input type="text" placeholder="搜索..." value="{{Request::get('keyword','')}}" class="nav-search-input"
                       id="" autocomplete="off" name="keyword"/>
                <i class="icon-search nav-search-icon"></i>
                </span>
        </form>
    </div>
    <div class="page-content">
        <div class="page-header">
            <h1>提现申请列表
                <small>
                    <i class="icon-double-angle-right"></i>
                </small>
            </h1>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="order-nav">
                    <div{!! request('state')=='1'?' class="current"':'' !!}><a href="?state=1">待审核</a></div>
                    <div{!! request('state')=='2'?' class="current"':'' !!}><a href="?state=2">已通过</a></div>
                    <div{!! request('state')=='3'?' class="current"':'' !!}><a href="?state=3">不通过</a></div>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover list">
                        <thead>
                        <tr>
                            <th>
                                <div>ID</div>
                            </th>
                            <th>
                                <div>用户Id</div>
                            </th>
                            <th>
                                <div>微信昵称</div>
                            </th>
                            <th>
                                <div>提现账号类型</div>
                            </th>
                            <th>
                                <div>金额</div>
                            </th>
                            <th>
                                <div>申请时间</div>
                            </th>
                            <th>
                                <div>状态</div>
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
                                    <div>{{$item->user?$item->user->id:''}}</div>
                                </td>
                                <td>
                                    <div>{{$item->user?$item->user->nickname:''}}</div>
                                </td>
                                <td>
                                    <div>{{$item->account_type_str}}</div>
                                </td>
                                <td>
                                    <div>{{$item->money_str}}</div>
                                </td>
                                <td>
                                    <div>{{$item->created_at}}</div>
                                </td>
                                <td>
                                    <div>{{$item->state_str}}</div>
                                </td>
                                <td width="100">
                                    <div class="  action-buttons">
                                        <a class="blue showBtn" href="/backstage/withdraw/{{$item->id}}"><i
                                                    class="icon-zoom-in bigger-130"></i></a>
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
        var url = '/backstage/withdraw?state=1';
        $(function () {

        })
    </script>
@stop