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
            <h1>参数列表
                <small>
                    <i class="icon-double-angle-right"></i>
                </small>
            </h1>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover list">
                        <thead>
                        <tr>
                            <th>
                                <div>ID</div>
                            </th>
                            <th>
                                <div>参数名</div>
                            </th>
                            <th>
                                <div>参数值</div>
                            </th>
                            <th>
                                <div>最近修改时间</div>
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
                                    <div>
                                        <input type="text" class="modify" value="{{$item->value}}">%
                                    </div>
                                </td>
                                <td>
                                    <div>{{$item->updated_at}}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="no-record">没有记录！</td>
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
        var url = '/backstage/setting';
        $(function () {
            $(".modify").change(function () {
                var id = $(this).closest("tr").data("item-id");
                var url = '/backstage/setting/'+id;
                var data = {'value':$(this).val()};
                ajax(url,data,'PUT',function(){
                    location.reload()
                })
            });
        })
    </script>
@stop