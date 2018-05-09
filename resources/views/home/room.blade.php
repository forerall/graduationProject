@extends('layouts.app')

@section('style')
    <style>
        .chat-list {
            background-color: rgb(235, 235, 235);
            overflow: scroll;
            padding: 0 !important;
            top: 2.5rem;
            bottom: 2rem;
            position: absolute;
            left: 0;
            width: 100%
        }

        .send-redpacket {
            position: fixed;
            bottom: 0;
            left: 0;
        }

        #sendPage, #recordPage {
            position: fixed;
            overflow: scroll;
            z-index: 2;
        }

        #scroller {
            z-index: 1;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
            width: 100%;
            -webkit-transform: translateZ(0);
            -moz-transform: translateZ(0);
            -ms-transform: translateZ(0);
            -o-transform: translateZ(0);
            transform: translateZ(0);
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-text-size-adjust: none;
            -moz-text-size-adjust: none;
            -ms-text-size-adjust: none;
            -o-text-size-adjust: none;
            text-size-adjust: none;
            padding-bottom: 4rem;
        }

        .lei {
            width: 30px !important;
            height: 30px !important;
            position: absolute;
            right: 3.5rem;
            top: 50%;
            transform: translateY(-50%);
        }

        .bz, .lei3 {
            width: 5rem;
        }
    </style>
@endsection
@section('content')
    <div class="fixed-header header">
        <h1>{{$room->name}}</h1>
        <i class="iconfont icon-back" onclick="history.go(-1)"></i>
    </div>
    <div class="content chat-list" id="wrapper" style="">
        <div id="scroller">

        </div>
    </div>

    <div class="btn send-redpacket" style="{{$room->password?'display: none':''}}">发红包(当前余额：<span id="balance">{{$user->balance_str}}</span>)</div>

    <div id="sendPage" class="page">
        <form action="/sendPacket" method="post">
            <input type="hidden" name="room_id" value="{{$room->id}}">
            {{csrf_field()}}
            <div class="fixed-header header">
                <h1>发红包({{$room->min_str}}-{{$room->max_str}})</h1>
                <i class="iconfont icon-back" onclick="$(this).closest('.page').hide()"></i>
            </div>
            <div class="content red-packet-form">
                <div class="money">
                    <div class="t">金额</div>
                    <input type="text" name="money" placeholder="0.00">
                    <div class="yuan">元</div>
                </div>
                <div class="input-group">
                    <div class="input-label">包数</div>
                    <div class="input-content">
                        <select name="packets">
                           
                            <option value="8">8包</option>
                            <option value="9">9包</option>
                            <option value="10">10包</option>
                        </select>
                    </div>
                </div>
                <div class="input-group">
                    <div class="input-label">雷</div>
                    <div class="input-content">
                        <input type="number" maxlength="1" name="lei" placeholder="0-9数字"
                               oninput="if(value.length>1)value=value.slice(0,1)">
                    </div>
                </div>
                <div class="msg">
                    <textarea name="msg" placeholder="恭喜发财">恭喜发财</textarea>
                </div>
                <div class="btn send-packet" data-state="0">塞钱进红包</div>
            </div>
        </form>
    </div>

    <div id="recordPage" class="page">
        <div class="fixed-header header" style="background: rgb(201,97,72) !important;color: rgb(253,227,83)">
            <h1 style="color: rgb(253,227,83)">红包</h1>
            <i class="iconfont icon-back" onclick="$(this).closest('.page').hide()"></i>
        </div>
        <div class="content" style="background-color: rgb(201,97,72);padding-top: 3.5rem">
            <div class="record-top">
                <img class="record-avatar" src="" style="border: 3px solid rgb(225,202,170)">
                <img class="record-top-bg" src="/images/record-top.png">
                <p class="sender-nickname"></p>
            </div>
            <div class="get-box"
                 style="display:none;background-color: rgb(241,241,241);padding: 0.5rem 0 1.5rem 0;font-size: 2rem;text-align: center">
                <div class="get"><span class="get-money"></span><span style="font-size: 0.8rem">元</span></div>
                <img class="lei3" src="/images/lei3.png">
                <img class="bz" src="/images/bz.png">
            </div>
            <div class="status-info"
                 style="border-bottom: 1px solid #eee;color: rgb(195,192,191);background-color: #fff;padding: 0.5rem 0.5rem;font-size: 0.6rem;"></div>
            <div class="record-info" style="padding-bottom: 3rem">
                <p>崂山东路</p>
                <p>崂山东路</p>
            </div>
        </div>

    </div>


    <div class="hongbao pop-container">
        <div class="hb">
            <div class="topcontent">
                <div class="avatar">
                    <img src="">
                    <span class="pop-close">+</span></div>
                <h2>微信红包</h2>
                <span class="text">给你发了一个红包</span>
                <div class="description">恭喜发财，大吉大利!</div>
            </div>
            <div class="chai" id="chai">
                <span>開</span></div>
            <div class="hb-record" style="display: none">查看领取详情</div>
        </div>
    </div>
    <audio id="open_packet" src="/images/open.mp3"></audio>
    <audio id="new_msg" src="/images/open-packet.mp3"></audio>


    <script id="test" type="text/html">
        <div class="chat-line left">
            <div class="avatar">
                <img src="./images/default.png">
            </div>
            <div class="content red-packet">
                <div class="packet-content">
                    <div class="packet-icon"></div>
                    <div class="packet-txt">恭喜发财</div>
                </div>
                <div class="packet-bottom">红包</div>
            </div>
        </div>
        <div class="chat-line right">
            <div class="content red-packet red-open">
                <div class="packet-content">
                    <div class="packet-icon"></div>
                    <div class="packet-txt">恭喜发财</div>
                </div>
                <div class="packet-bottom">红包</div>
            </div>
            <div class="avatar">
                <img src="./images/default.png">
            </div>
        </div>
    </script>
@endsection


@section('script')
    <script>
        function audioLoad(id) {
            var audio = document.getElementById(id);
            var play = function () {
                document.removeEventListener("WeixinJSBridgeReady", play);
                audio.play();
                audio.pause();
            };
            //weixin
            document.addEventListener("WeixinJSBridgeReady", play, false);
        }
        function play() {

        }
        function isPassive() {
            var supportsPassiveOption = false;
            try {
                addEventListener("test", null, Object.defineProperty({}, 'passive', {
                    get: function () {
                        supportsPassiveOption = true;
                    }
                }));
            } catch (e) {
            }
            return supportsPassiveOption;
        }
        document.getElementById('wrapper').addEventListener('touchmove', function (e) {
            e.preventDefault();
        }, isPassive() ? {
            capture: false,
            passive: false
        } : false);
        var myScroll = null;
        var user_id = '{{\Illuminate\Support\Facades\Auth::id()}}'
        function showPacketRecord(data) {
            console.log(data)
            var dom = $('#recordPage')
            dom.find('.record-avatar').attr('src', data.owner.avatar)
            dom.find('.sender-nickname').html(data.owner.nickname + '的红包');
            dom.find('.lei3').hide();
            dom.find('.bz').hide();
            if (data.get) {
                dom.find('.get-money').html(data.get.money_str);
                dom.find('.get-box').show();
                if (data.get.type == 1) {
                    dom.find('.lei3').show();
                }
                if (data.get.reward == 1) {
                    dom.find('.bz').show();
                }
            } else {
                dom.find('.get-box').hide();
            }
            var html = ''
            for (var i in data.others) {
                var q = data.others[i]
                var user = q.user || data.systemUser
                html += '<div class="record"><img src="' + user.avatar + '"><div class="nickname"><p>' + user.nickname + '</p><p class="t">' + q.created_at + '</p></div><img style="' + (q.type == 1 ? '' : 'display:none') + '" class="lei" src="/images/lei.png"><span style="font-size: 0.8rem">' + q.money_str + '元</span></div>'
            }
            dom.find('.status-info').html(data.status);
            dom.find('.record-info').html(html);
            dom.show();
        }
        function showPacket(data) {
            var url = '/checkGetPacketStatus'
            $.getJSON(url, {packet_id: data.packet_id}, function (result) {
                if (result.errCode == 0) {
                    if (result.data.status == 1) {
                        var dom = $('.hongbao')
                        dom.data('info', data)
                        dom.find('img').attr('src', data.avatar)
                        dom.find('h2').html(data.nickname)
                        dom.find('.description').html((data.lei > -1 ? '雷' + data.lei : '福利包'))
                        if(result.data.is_owner){
                            //$('.hb-record').show();
                        }else{
                            $('.hb-record').hide();
                        }
                        dom.show();
                    } else {
                        $('.p-' + data.packet_id).addClass('red-open')
                        showPacketRecord(result.data)
                    }
                }
            })

        }
        function loadRedPacket(first) {
            first = first || false;
            var url = '/packetRecord?room_id={{$room->id}}'
            var begin_id = $('.chat-list').data('begin_id') || 0
            $.getJSON(url, {begin_id: begin_id}, function (result) {
                if (result.errCode == 0) {
                    var last_id = begin_id;
                    var data = result.data
                    $('.chat-list').data('begin_id', data.begin_id)
                    $('#balance').html(data.balance_str)
                    var html = ''
                    for (var i in data.list) {
                        var item = data.list[i]
                        var user = item.user || data.systemUser
                        html += '<div class="chat-line' + (item.user_id == user_id ? ' right' : ' left') + '"><div class="avatar"><img src="' + user.avatar + '"></div>' +
                                '<div class="p-' + item.id + ' content red-packet ' + (item.packets <= item.got || item.qiang > 0 || item.state > 0 ? 'red-open' : '') + '" data-lei="' + item.lei + '" data-nickname="' + user.nickname + '" data-packet-id="' + item.id + '" data-avatar="' + user.avatar + '" data-msg="' + item.msg + '"><div class="packet-content">' +
                                '<div class="packet-icon"></div><div class="packet-txt"><p>' + item.packet_money_str + '元</p><p style="font-size: 0.8rem">' + (item.lei > -1 ? '雷' + item.lei : '福利包') + " ("+ item.packets+'包)</p></div>' +
                                '</div><div class="packet-bottom">红包</div></div></div>'
                        last_id = item.id;
                    }
                    $('#scroller').append(html);
                    $('.chat-list').data('begin_id', last_id)
                    if (html) {
                        if (myScroll)
                            myScroll.destroy();
                        myScroll = new IScroll('#wrapper', {mouseWheel: true, click: true, tap: true});
                        myScroll.scrollToElement(document.querySelector('#scroller .chat-line:last-child'), 0, null, null, false)
                        if (!first) {
                            document.getElementById('new_msg').play();
                        }
                    }
                }
            })
        }
        $(function () {
            $('.send-redpacket').click(function () {
                $('#sendPage').show();
            })
            $('.chai').click(function () {
                var packet = $('.hongbao').data('info')
                var url = '/getPacket'
                var chai = $(this)
                chai.addClass('open-redpacket')
                setTimeout(function () {
                    chai.removeClass('open-redpacket')
                    //document.getElementById('open_packet').play();
                    document.getElementById('new_msg').play();
                    $.getJSON(url, {packet_id: packet.packet_id}, function (result) {
                        $('.hongbao').hide()
                        $('.p-' + packet.packet_id).addClass('red-open')
                        if (result.errCode == 0) {

                            showPacketRecord(result.data)
                        } else {
                            alert(result.errMsg)
                        }

                    })
                }, 1000)

            })
            $('.hb-record').click(function () {
                var packet = $('.hongbao').data('info')
                var url = '/getPacket?test=1'
                var chai = $(this)
                chai.addClass('open-redpacket')
                setTimeout(function () {
                    chai.removeClass('open-redpacket')
                    //document.getElementById('open_packet').play();
                    document.getElementById('new_msg').play();
                    $.getJSON(url, {packet_id: packet.packet_id}, function (result) {
                        $('.hongbao').hide()

                        if (result.errCode == 0) {
                            $('.p-' + packet.packet_id).addClass('red-open')
                            showPacketRecord(result.data)
                        } else {
                            alert(result.errMsg)
                        }

                    })
                }, 1000)

            })
            $('.send-packet').click(function () {
                var _this = $(this)
                if($(this).data('state')>0){
                    return;
                }
                _this.data('state',1);
                _this.closest('form').ajaxSubmit(function (data) {
                    _this.data('state',0);
                    if (data.errCode == 0) {
                        _this.closest('form')[0].reset()
                        _this.closest('.page').hide();
                    } else {
                        alert(data.errMsg)
                    }
                })
            })
            $('#scroller').on('click', '.red-packet', function () {
                var data = {}
                data.packet_id = $(this).data('packet-id')
                data.avatar = $(this).data('avatar')
                data.nickname = $(this).data('nickname')
                data.msg = $(this).data('msg')
                data.lei = $(this).data('lei')
                showPacket(data)
            })
            loadRedPacket(true)

            var t = setInterval(function () {
                loadRedPacket()
                //clearInterval(t)
            }, 2000)
            audioLoad('new_msg')
            audioLoad('open_packet')

        })
    </script>
@endsection
