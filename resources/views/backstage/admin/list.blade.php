@extends('backstage.layouts.body')
@section('title', '列表')
@section('content')
    <div class="nav-search" id="nav-search">
        <form id="search_form" action="" method="get" enctype="multipart/form-data" class="form-search">
            <span class="input-icon">
                <input type="text" placeholder="搜索..." value="{{Request::get('keyword','')}}" class="nav-search-input"
                       id="" autocomplete="off" name="keyword"/>
                <i class="icon-search nav-search-icon"></i>
                </span>
        </form>
    </div>
    <div class="page-content">
        <div class="page-header">
            <h1>管理员列表
                <small>
                    <i class="icon-double-angle-right"></i>
                </small>
            </h1>
        </div>
        <div class="row">
            <div class="col-xs-12 r-toolbar">
                <a href="/backstage/admin/create" class="btn btn-sm">新增</a>
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
                                <div>账号</div>
                            </th>
                            <th>
                                <div>邮箱</div>
                            </th>
                            <th>
                                <div>创建时间</div>
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
                                    <div>{{$item->name}}</div>
                                </td>
                                <td>
                                    <div>{{$item->email}}</div>
                                </td>
                                <td>
                                    <div>{{$item->created_at}}</div>
                                </td>
                                <td width="200">
                                    <div class="  action-buttons">
                                        <a class="blue showBtn" href="/backstage/admin/{{$item->id}}"><i
                                                    class="icon-zoom-in bigger-130"></i></a>

                                        @if(!$item->isAdmin())
                                            <a class="green editBtn" href="/backstage/admin/{{$item->id}}/edit"><i
                                                        class="icon-pencil bigger-130"></i></a>
                                            <a class="red delBtn" href="javascript:void(0)"><i
                                                        class="icon-trash bigger-130"></i></a>
                                        @endif
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
        var url = '/backstage/admin';
        $(function () {
            $(".delBtn").click(function () {
                var id = $(this).closest("tr").data("item-id");
                ajaxConfirm("{{url('/backstage/admin')}}/" + id, {}, "DELETE", function (data) {
                    location.reload();
                }, "确认删除");
            });
        })
    </script>
@stop