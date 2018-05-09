@extends('backstage.layouts.body')
@section('title', '')
@section('content')
    <div class="page-content">
        <div class="page-header">
            <h1>
                充值详情
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
                            <div class="profile-info-name">用户ID</div>
                            <div class="profile-info-value">
                                <span class="">{{$item->user?$item->user->id:''}}</span>
                            </div>
                        </div>
                    </div>

                    <div class="profile-user-info profile-user-info-striped">
                        <div class="profile-info-row">
                            <div class="profile-info-name">用户</div>
                            <div class="profile-info-value">
                                <span class="">{{$item->user?$item->user->nickname:''}}</span>
                            </div>
                        </div>
                    </div>


                    <div class="profile-user-info profile-user-info-striped">
                        <div class="profile-info-row">
                            <div class="profile-info-name">充值类型</div>
                            <div class="profile-info-value">
                                <span class="">{{$item->pay_way_str}}</span>
                            </div>
                        </div>
                    </div>

                    <div class="profile-user-info profile-user-info-striped">
                        <div class="profile-info-row">
                            <div class="profile-info-name">金额</div>
                            <div class="profile-info-value">
                                <span class="">{{$item->money_str}}</span>
                            </div>
                        </div>
                    </div>
                   @if($item->state==1)
                        <style>
                            .op > div{
                                display: inline-block;
                                margin-left: 10px;
                            }
                        </style>
                        <div class="profile-user-info profile-user-info-striped">
                            <div class="profile-info-row">
                                <div class="profile-info-name">操作</div>
                                <div class="profile-info-value op">
                                    <textarea style="display: none;" id="msg" placeholder="拒绝原因"></textarea>
                                    <div class="btn op-confirm" data-ok="1">通过</div>
                                    <div class="btn op-confirm" data-ok="2">拒绝</div>
                                </div>

                            </div>
                        </div>
                   @else
                        <div class="profile-user-info profile-user-info-striped">
                            <div class="profile-info-row">
                                <div class="profile-info-name">状态</div>
                                <div class="profile-info-value">
                                    <span class="">{{$item->state_str}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="profile-user-info profile-user-info-striped">
                            <div class="profile-info-row">
                                <div class="profile-info-name">原因</div>
                                <div class="profile-info-value">
                                    <span class="">{{$item->msg}}</span>
                                </div>
                            </div>
                        </div>
                   @endif
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
        var url = '/backstage/recharge?state=1';
        $(function () {
            $('.op-confirm').click(function(){
                var ok = $(this).data('ok');
                var msg = $('#msg').val();
                var id = '{{$item->id}}';
                ajaxConfirm('/backstage/rechargeConfirm',{id:id,ok:ok,msg:msg},'POST',function(){
                    location.reload();
                },'确认操作？','');
            })
        })
    </script>
@stop