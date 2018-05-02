@extends('layouts.app')

@section('style')
    <style>
        .coupon-detail {
            padding: 10px;
        }

        .detail-item {
            position: relative;
            height: 60px;
            border-radius: 3px;
            border: 1px solid #f55;
            color: #ffffff;
            background-color: #f55 !important;
            margin-bottom: 8px;
        }

        .detail-item .v {
            width: 64px;
            font-size: 30px;
            line-height: 60px;
            border-right: 1px dashed #dddddd;
            font-weight: 800;
            text-align: center;
            position: relative;
        }

        .detail-item .y {
            position: absolute;
            font-size: 12px;
            /* margin-top: 5px; */
            top: 5px;
            left: 5px;
        }

        .info {
            position: absolute;
            left: 75px;
            top: 16px;
        }

        .info h2 {
            font-size: 14px;
            font-weight: 300;
        }

        .info p {
            font-size: 12px;
        }

        .lq {
            position: absolute;
            right: 7px;
            top: 11px;
            padding: 10px;
        }

        .expired {
            position: absolute;
            right: 7px;
            top: 11px;
            padding: 10px;
        }

        .detail-item.disable {
            background-color: #dddddd !important;
            border-color: #dddddd;
        }

        .disable .lq {
            display: none;
        }

        .disable .expired {
            position: absolute;
            right: 10px;
            top: 22px;
            display: block;
        }
        .top{
            width: 100%;
            height: 40px;
        }
        .top >div{
            display: inline-block;
            width: 50%;
            margin-left:-4px;
            text-align: center;
            height: 40px;
            line-height: 40px;

        }
        .act {
            color: #f55;

        }

    </style>
@endsection
@section('content')
    <div class="top">
        <div class="can_jump {{request('get')?'':'act'}}" _url="/coupon">我的优惠券</div>
        <div class="can_jump {{request('get')?'act':''}}" _url="/coupon?get=1">去领券</div>
    </div>
    <div class="coupon-detail">
        @forelse($list as $item)
            <div class="detail-item{{$item->expired?' disable':''}}" data-item-id="{{$item->id}}">
                <div class="v">
                    <div class="y">￥</div>
                    {{$item->money_str}}
                </div>
                <div class="info">
                    <h2>{{$item->name}}</h2>
                    <p>{{substr($item->from,0,10)}} 至 {{substr($item->to,0,10)}}</p>
                </div>
                @if($item->expired)
                    <div class="expired">已过期</div>
                @elseif(request('get'))
                    <div class="lq dolq">领取</div>
                @else
                @endif
            </div>
        @empty
            <div class="emp">还没有优惠券</div>
        @endforelse
    </div>


@endsection


@section('script')
    <script type="text/javascript"
            src="http://api.map.baidu.com/api?v=2.0&ak=LnBnamvKBneCbsAHAWG7aHNVa1L3GUE4"></script>
    <script>
        $(function () {
            $('.dolq').click(function(){
                var id = $(this).closest('.detail-item').data('item-id');
                ajax('/lq',{id:id},'POST',function(data){
                    location.reload();
                })
            })
        })

    </script>
@endsection
