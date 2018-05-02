@extends('layouts.app')

@section('style')
    <style>
        .order-detail {
        }

        .detail-item {
            position: relative;
            height: 80px;
            background-color: #ffffff;
            margin-bottom: 8px;
            font-size: 12px;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .detail-item .info {
            font-size: 12px;
            line-height: 25px;
        }

        .detail-item .time {
            color: #BBBBBB;
            margin-top: 6px;
        }

        .pay {
            position: absolute;
            right: 17px;
            top: 17px;
            padding: 5px 10px;
            background-color: #f55;
            color: #ffffff;
            border-radius: 5px;
        }

        .view {
            position: absolute;
            right: 17px;
            top: 17px;
            padding: 5px 10px;
            background-color: #dddddd;
            color: #ffffff;
            border-radius: 5px;
        }


    </style>
@endsection
@section('content')

    <div class="order-detail load-list" data-now_page="1" data-total_page="{{$list->lastPage()}}">
        @forelse($list as $item)
            <div class="detail-item">
                <p class="info">存包柜:{{$item->terminal->name}}-{{$item->box->number>9?$item->box->number:'0'.$item->box->number}}箱</p>
                <p>状态:{{$item->state_str}}</p>
                <p class="time">{{$item->begin_at}}</p>
                @if($item->state==2)
                    <div class="pay can_jump" _url="/orderDetail?id={{$item->id}}">付款</div>
                @else
                    <div class="view can_jump" _url="/orderDetail?id={{$item->id}}">查看</div>
                @endif
            </div>
        @empty
            <div class="emp">没有记录</div>
        @endforelse
    </div>


@endsection


@section('script')
    <script type="text/javascript"
            src="http://api.map.baidu.com/api?v=2.0&ak=LnBnamvKBneCbsAHAWG7aHNVa1L3GUE4"></script>
    <script>
        function loadMore() {
            if ($('.load-list').data('loading')) {
                return;
            }
            $('.load-list').data('loading', 1);
            var now_page = $('.load-list').data('now_page');
            var total_page = $('.load-list').data('total_page');
            if (now_page >= total_page) {
                return;
            }
            $.ajax({
                url: '/order',
                data: {
                    'page': now_page + 1
                },
                dataType: 'json',
                type: 'GET',
                success: function (data, status, xhr) {
                    console.log(data.data.data);
                    var html = '';
                    for (var i in data.data.data) {

                        html += '<div class="detail-item">'
                                +'<p class="info">存包柜:'+data.data.data[i].terminal.name+'-'+(data.data.data[i].box.number>9?data.data.data[i].box.number:'0'+data.data.data[i].box.number)+'箱</p>'
                                +'<p>状态:'+data.data.data[i].state_str+'</p>'
                                +'<p class="time">'+data.data.data[i].begin_at+'</p>'
                                +'<div class="'+(data.data.data[i].state==2?'pay':'view')+' can_jump" _url="/orderDetail?id='+data.data.data[i].id+'">'+(data.data.data[i].state==2?'付款':'查看')+'</div>'
                                +'</div>';

                    }
                    $('.load-list').append(html);
                    $('.load-list').data('now_page', data.data.current_page);
                    $('.load-list').data('total_page', data.data.last_page);
                    $('.load-list').data('loading', 0);
                },
                error: function (xhr, status, error) {
                    //ajaxProcess(xhr.status,xhr.responseText);
                    if (xhr.status == 401) {
                        alert('请登陆后投票');
                    }
                }
            });
        }
        $(function () {
            $(document).scroll(function () {
                if ($(document).height() - $(window).height() - $(window).scrollTop() < 2) {
                    loadMore();
                }
            });
        })

    </script>
@endsection
