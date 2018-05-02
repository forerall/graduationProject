@extends('layouts.app')

@section('style')
    <style>
        .balance-top {
            background-color: rgb(43, 43, 43);
            padding: 10px 0px;
        }

        .balance-top p {
            text-align: center;
            color: #ffffff;
        }

        .balance {
            line-height: 45px;
            font-size: 22px;
            color: #ffffff;

        }

        .balance-title {
            position: relative;
            background-color: #ffffff;
            text-indent: 29px;
            height: 33px;
            font-size: 12px;
            line-height: 33px;
            margin-top: 10px;
            border-top: 1px solid #dddddd;
            border-bottom: 1px solid #dddddd;
        }

        .balance-detail {
            background-color: #ffffff;
        }

        .detail-item {
            position: relative;
            padding: 5px 10px;
            border-bottom: 1px solid #eee;
        }

        .detail-item h2 {
            font-size: 14px;
            font-weight: 600;
            width: 75%;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
            line-height: 24px;
        }

        .detail-item p {
            font-size: 12px;
            color: #aaaaaa;
        }

        .detail-item .money {
            position: absolute;
            right: 10px;
            top: 16px;
            font-size: 14px;
            display: none;
        }
    </style>
@endsection
@section('content')
    <div class="fixed-header header">
        <h1>奖励领取</h1>
        <i class="iconfont icon-back" onclick="history.go(-1)"></i>
    </div>
    <div class="content">
        <div class="balance-detail" data-now_page="1" data-total_page="">
            @forelse($list as $item)
                <div class="detail-item" data-qid="{{$item->id}}">
                    <div>
                        <h2>红包金额:{{$item->money_str}}</h2>
                        <p>奖励金额{{$item->money<=999?'18.88':($item->money<=9999?'188.88':'288.88')}}</p>
                    </div>
                    <div class="money">
                        {{$item->get==1?'已领取':'立即领取'}}
                    </div>
                </div>
            @empty
                <div style="padding: 2rem 0;text-align: center;color: #bbbbbb">没有记录</div>
            @endforelse
        </div>
    </div>

@endsection


@section('script')
    <script>
        function loadMore() {
            if ($('.balance-detail').data('loading')) {
                return;
            }
            $('.balance-detail').data('loading', 1);
            var now_page = $('.balance-detail').data('now_page');
            var total_page = $('.balance-detail').data('total_page');
            if (now_page >= total_page) {
                return;
            }
            $.ajax({
                url: '/lq',
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
                                + '<div>'
                                + '<h2>' + data.data.data[i].detail + '</h2>'
                                + '<p>' + data.data.data[i].created_at + '</p>'
                                + '</div>'
                                + '<div class="money">'
                                + data.data.data[i].money_str + '元</div>'
                                + '</div>';

                    }
                    $('.balance-detail').append(html);
                    $('.balance-detail').data('now_page', data.data.current_page);
                    $('.balance-detail').data('total_page', data.data.last_page);
                    $('.balance-detail').data('loading', 0);
                },
                error: function (xhr, status, error) {
                    //ajaxProcess(xhr.status,xhr.responseText);
                    if (xhr.status == 401) {
                        //alert('请登陆后投票');
                    }
                }
            });
        }
        $(function () {
            $(document).scroll(function () {
                if ($(document).height() - $(window).height() - $(window).scrollTop() < 2) {
                    //loadMore();
                }
            });
            $('.money').click(function(){return;
                var qid = $(this).closest('.detail-item').data('qid');

                ajax('/lq',{qid:qid},'POST',function(){
                    location.reload();
                })
            })
        })

    </script>
@endsection
