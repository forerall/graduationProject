@extends('backstage.layouts.body')
@section('title', '')
@section('content')
    <div class="page-content">
        <div class="page-header">
            <h1>
                管理员详情
                <small>
                    <i class="icon-double-angle-right"></i>
                </small>
            </h1>
        </div>
        <div class="row">
            <div class="col-xs-12">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="form-horizontal">
                    <div class="profile-user-info profile-user-info-striped">
    <div class="profile-info-row">
        <div class="profile-info-name">ID</div>
        <div class="profile-info-value">
            <span class="">{{$item->id}}</span>
        </div>
    </div>
</div>
<div class="profile-user-info profile-user-info-striped">
    <div class="profile-info-row">
        <div class="profile-info-name">头像</div>
        <div class="profile-info-value">
            <img class="avatar-img" style="" src="{{$item->avatar}}">
        </div>
    </div>
</div>

<div class="profile-user-info profile-user-info-striped">
    <div class="profile-info-row">
        <div class="profile-info-name">账号</div>
        <div class="profile-info-value">
            <span class="">{{$item->name}}</span>
        </div>
    </div>
</div>

<div class="profile-user-info profile-user-info-striped">
    <div class="profile-info-row">
        <div class="profile-info-name">邮箱</div>
        <div class="profile-info-value">
            <span class="">{{$item->email}}</span>
        </div>
    </div>
</div>



<div class="profile-user-info profile-user-info-striped">
    <div class="profile-info-row">
        <div class="profile-info-name">创建时间</div>
        <div class="profile-info-value">
            <span class="">{{$item->created_at}}</span>
        </div>
    </div>
</div>

<div class="profile-user-info profile-user-info-striped">
    <div class="profile-info-row">
        <div class="profile-info-name">更新时间</div>
        <div class="profile-info-value">
            <span class="">{{$item->updated_at}}</span>
        </div>
    </div>
</div>


                </div>
                <br>
                <div class="form-group">
                    <div class="col-xs-12 col-sm-9">
                        <div class="btn btn-prev event-back">
                            <i class="icon-arrow-left"></i>
                            返回
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('script')
    <script>
        var url = '/backstage/admin';
        $(function(){
            
        })
    </script>
@stop