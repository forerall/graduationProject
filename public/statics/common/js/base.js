var Cache = {};
Cache.timestamp = function () {
    var t = (new Date().getTime()).toString();
    return parseInt(t.substr(0, t.length - 3));
}
Cache.setObject = function (key, value, expire) {
    if (typeof value != 'object') {
        console.log('Cache.setJson key:' + key + ',value type error:' + (typeof value))
        value = {};
    }
    Cache.set(key, JSON.stringify(value), expire)
}
Cache.getObject = function (key) {
    var result = Cache.get(key)
    if (result) {
        return JSON.parse(result)
    }
    return {};
}
Cache.getArray = function (key) {
    var result = Cache.get(key)
    if (result) {
        return JSON.parse(result)
    }
    return [];
}
Cache.set = function (key, value, expire) {
    if (!key) {
        console.log('Cache.get key is empty')
        return '';
    }
    expire = expire || 0;
    expire = parseInt(expire)
    expire = isNaN(expire) ? 0 : expire
    var expire_key = key + '_expire';
    localStorage.setItem(expire_key, expire > 0 ? Cache.timestamp() + expire : 0);
    localStorage.setItem(key, value);
}
Cache.get = function (key) {
    if (!key) {
        console.log('Cache.get key is empty')
        return '';
    }
    var expire_key = key + '_expire';
    var expire = localStorage.getItem(expire_key);
    expire = parseInt(expire)
    expire = isNaN(expire) ? 0 : expire
    if (Cache.timestamp() < expire || expire <= 0) {
        return localStorage.getItem(key);
    } else {
        return '';
    }
}
Cache.del = function (key) {
    localStorage.removeItem(key);
}


function App(init, pageRenderCallback) {
    var _this = this;
    var u = navigator.userAgent, app = navigator.appVersion;
    _this.loginUrl = '/game/login';
    _this.wxLoginUrl = '/wxGetCode';
    _this.wxInfoUrl = '/getWxUser';
    _this.rootDom = '';
    _this.pageRenderCallback = pageRenderCallback;
    _this.debug = true;
    _this.user = {};
    _this.timeout = 5000;//毫秒
    _this.user = Cache.getObject('auth');
    _this.isIos = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/);
    _this.isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //g
    _this.isWechat = u.toLowerCase().indexOf('micromessenger') != -1; //g
    _this.request = {};
    var req = location.search.substr(1).split('&'), pm;
    _this.request['hash'] = location.hash.substr(1);
    for (var i in req) {
        if (req[i]) {
            pm = req[i].split('=');
            if (pm[0]) {
                _this.request[pm[0]] = pm[1] || '';
            }
        }
    }

    (function (doc, win) {
        var docEl = doc.documentElement,
            resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
            recalc = function () {
                var clientWidth = docEl.clientWidth;
                if (clientWidth > 568) {
                    clientWidth = 568;
                } else if (clientWidth < 320) {
                    clientWidth = 320;
                } else {
                    clientWidth = clientWidth;
                }
                docEl.style.fontSize = 20 * (clientWidth / 375) + 'px';
                _this.remValue = 20 * (clientWidth / 375);
            };
        if (!doc.addEventListener) return;
        recalc();
        win.addEventListener(resizeEvt, recalc, false);
        doc.addEventListener('DOMContentLoaded', recalc, false);
    })(document, window);

    $(function () {
        if (!_this.user || !_this.user.id || !_this.user.token) {
            var code = _this.getQueryString('code', false);
            if (code) {
                history.replaceState(null, '', location.href.replace(/[?&](code=[^&]*)/i, ''));
                _this.getJson(_this.wxInfoUrl, {code: code}, function (result) {
                    if (result.errCode == 0 && result.data.user) {
                        Cache.setObject('auth', result.data.user);
                        location.reload();
                    } else {
                        _this._unauthorized();
                        return;
                    }
                })
                return;//不返回下面的请求会继续触发授权登录
            } else {
              //  _this._unauthorized();
               // return;
            }
        }
        //加载页面
        var page = $('#app');
        _this.rootDom = page;
        //刷新
        if (_this.isIos) {
            window.addEventListener('pageshow', function () {
                if (localStorage && localStorage.getItem('reload')) {
                    localStorage.setItem('reload', '');
                    location.reload();
                }
            });
        } else {
            if (localStorage && localStorage.getItem('reload')) {
                localStorage.setItem('reload', '');
                location.reload();
            }
        }

        $.ajaxSetup({
            headers: {'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')}
        });
        page.on('click', '[go-page]', function () {
            var url = $(this).attr('go-page');
            if (url) {
                location.href = url;
            }
        })
        page.on('click', '.back', function () {
            _this.goBack();
        })
        page.on('click', '.tabs > div', function () {
            $(this).addClass('active').siblings().removeClass('active')
            $(this).closest('.tabs-container').find('.' + $(this).attr('target')).show().siblings('.tabs-page').hide();
        })
        $('.tabs > div:first').click()
        //预加载
        page.find('[_ajax]').each(function () {
            if ($(this).hasClass('ignore')) {
                return true;
            }
            _this.checkPage($(this), true);
        })
        if (typeof init == 'function') {
            init();
        }
    })
}
App.prototype = {
    _unauthorized: function () {
        var _this = this;
        _this.showLoginForm();
    },
    _log: function (data) {
        if (this.debug) {
            console.log(data)
        }
    },
    _getValue: function (val, defaultVal) {
        if (typeof val == 'undefined') {
            return this._getValue(defaultVal, false);
        }
        return val;
    },
    _appendAuth: function (data) {
        data.user_id = this.user.id;
        data.token = this.user.token;
        return data;
    },
    _processResult: function (result, callback) {
        var _this = this;
        switch (result.errCode) {
            case -1://请求错误
                // timeout error  abort  parsererror
                if (result.status == 'timeout') {
                    _this.toast('请求超时', true)
                } else {
                    _this.toast('请求失败', true)
                }
                console.log('请求失败');
                console.log(result);
                if (typeof callback == 'function') {
                    callback(result);
                }
                break;
            case 0://成功
                if (typeof callback == 'function') {
                    callback(result);
                }
                break;
            case 1://失败
            case 2://参数错误
                if (result.errCode == 2) {
                    for (var i in result.data.errors) {
                        if (result.data.errors[i][0]) {
                            result.errMsg = result.data.errors[i][0];
                        }
                        break;
                    }
                }
                _this.toast(result.errMsg, true);
                if (typeof callback == 'function') {
                    callback(result);
                }
                break;
            case 3://需要登陆
                _this.toast(result.errMsg, true);
                if (typeof callback == 'function') {
                    callback(result);
                }
                setTimeout(function () {
                    _this.showLoginForm();
                }, 1000);
                break;
            default:
                console.log('result格式错误');
                console.log(result);
        }
    },
    _domCanReload: function (dom) {
        var loaded = dom.data('loaded');
        if (loaded === 1 || loaded == 2) {
            this._log('dom:' + dom.attr('class') + ' reload cancel')
            return false;
        }
        return true;
    },
    toast: function (txt, warning, callback) {
        if (!txt) {
            console.log('toast msg is empty');
            return;
        }
        if ($('body').find('.tip-box').length == 0) {
            $('body').append('<div class="tip-box"><div class="tip-content"></div></div>');
        }
        var box = $('body').find('.tip-box')
        if (warning) {
            box.addClass('warning');
        } else {
            box.removeClass('warning');
        }
        box.find('.tip-content').html(txt);
        box.stop(true, true).slideDown(100).delay(2000).slideUp(100, function () {
            if (typeof callback == 'function') {
                callback();
            }
        })
    },
    clearAllCookie: function () {
        var keys = document.cookie.match(/[^ =;]+(?=\=)/g);
        if (keys) {
            for (var i = keys.length; i--;) {
                document.cookie = keys[i] + '=0;expires=' + new Date(0).toUTCString()
            }
        }
    },
    getCookie: function (name) {
        var strcookie = document.cookie;//获取cookie字符串
        var arrcookie = strcookie.split("; ");//分割
        //遍历匹配
        for (var i = 0; i < arrcookie.length; i++) {
            var arr = arrcookie[i].split("=");
            if (arr[0] == name) {
                return arr[1];
            }
        }
        return "";
    },
    getJson: function (url, data, callback) {
        var _this = this;
        data = data || {};
        _this._log('get:' + url)
        _this._log(data)
        $.ajax(url, {
            data: _this._appendAuth(data),
            dataType: 'json',//服务器返回json格式数据
            //processData: false,//禁止data自动转化成字符串
            type: 'get',//HTTP请求类型
            timeout: this.timeout,//超时时间设置为10秒；
            headers: {'Content-Type': 'application/json'},
            success: function (result) {
                _this._processResult(result, callback);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                //console.log(XMLHttpRequest)
                //console.log(textStatus)
                //console.log(errorThrown)
                var result = {
                    errCode: -1,
                    errMsg: errorThrown,//errorThrown : Not Found  ,  Internal Server Error ,timeout
                    status: textStatus, //textStatus : timeout  error  abort  parsererror null
                    data: {},
                }
                _this._processResult(result, callback);
            }
        });
    },
    postData: function (url, data, callback) {
        var _this = this;
        _this._log('post:' + url)
        _this._log(data)
        $.ajax({
            url: url,
            data: _this._appendAuth(data),
            dataType: 'json',
            timeout: this.timeout,//超时时间设置为10秒；
            type: 'POST',
            success: function (result) {
                _this._processResult(result, callback);
            },
            error: function (xhr, type, errorThrown) {
                //console.log(XMLHttpRequest)
                //console.log(textStatus)
                //console.log(errorThrown)
                var result = {
                    errCode: -1,
                    errMsg: errorThrown,//errorThrown : Not Found  ,  Internal Server Error
                    status: textStatus, //textStatus : timeout  error  abort  parsererror null
                    data: {},
                }
                _this._processResult(result, callback);
            }
        });
    },
    submitForm: function (form, callback) {
        form = typeof form == 'string' ? $(form) : form;
        var _this = this;
        var url = form.attr('action');
        var data = {};
        form.find('input,select,textarea').each(function () {
            var k = $(this).attr('name');
            if (k) {
                data[k] = $(this).val();
            }
        })
        _this.postData(url, data, callback);
    },
    showLoginForm: function () {
        Cache.del('auth')
        var _this = this;
        history.pushState(null, '', location.pathname + location.search + location.hash);

        if (_this.isWechat) {
            location.href = _this.wxLoginUrl + '?refer=' + encodeURIComponent(location.href);
        } else if (location.pathname.indexOf(_this.loginUrl) != 0) {
            location.href = _this.loginUrl;
        }

    },

    backAndReload: function () {
        localStorage.setItem('reload', '1');
        this.goBack();

    },
    getQueryString: function (name, val) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]);
        return val;
    },
    renderDom: function (dom, data, show) {
        show = this._getValue(show, true);
        var renderData = {};
        renderData.request = this.request;
        for (var i in data) {
            renderData[i] = data[i];
        }
        var _ajax = dom.attr('_ajax');
        if (_ajax) {
            _ajax = _ajax.split(',');
            _ajax[0] && dom.attr('_tpl', _ajax[0])
            _ajax[1] && dom.attr('_api', _ajax[1])
        }
        var tpl = dom.attr('_tpl');
        if (!tpl) {
            tpl = dom.attr('_ajax').split(',')[0];
        }
        var template = $('#' + tpl).html();
        var compiledTemplate = Template7.compile(template);
        var html = compiledTemplate(renderData);
        if (dom.attr('_target')) {
            dom.find(dom.attr('_target')).html(html)
        } else {
            dom.html(html)
        }
        if (!show) {
            return;
        }
        var page = dom.hasClass('page') ? dom : dom.closest('.page');
        page.show().siblings('.page').hide();
    },
    getUser: function () {
        return this.user;
    },
    goPage: function (page) {
        location.href = page;
    },
    goBack: function () {
        if (document.referrer && document.referrer.indexOf(location.hostname) > -1) {
            history.back(-1);
        } else {
            location.href = '/app/index.html';
        }
    },
    updatePage: function (page, params) {
        var op = page.data('params') || {}
        if (params) {
            for (var i in params) {
                op[i] = params[i]
            }
        }
        params && page.data('params', op)
        page.data('loaded', 0);
        this.checkPage(page, false)
    },
    checkPage: function (page, preload) {
        var _this = this;
        var loaded = page.data('loaded') || 0;
        var _list = page.attr('_list');
        var _ajax = page.attr('_ajax');
        preload = _this._getValue(preload, false);
        if (_ajax) {
            _ajax = _ajax.split(',');
            _ajax[0] && page.attr('_tpl', _ajax[0])
            _ajax[1] && page.attr('_api', _ajax[1])
        }
        var tpl = page.attr('_tpl');
        var api = page.attr('_api');
        if (!preload) {
            page.show().siblings('.page').hide();
        }
        if (_list && loaded === 0) {
            page.data('loaded', 1);
            _this.dropListInit('#' + page[0].id, api, _list.indexOf('up') > -1, _list.indexOf('down') > -1, function () {

            })
            return;
        }
        if (!api || !tpl) {
            return;
        }
        if (loaded === 1 || loaded == 2) {
            return;
        }
        page.data('loaded', 1);//开始加载
        try {
            var params = page.data('params');
            if (typeof params != 'object') {
                params = {};
            }
            for (var i in _this.request) {
                params[i] = _this.request[i]
            }
            _this.getJson(api, params, function (result) {
                if (result.errCode == 0) {
                    result.data._params = params;
                    _this.renderDom(page, result.data, false);
                    page.data('loaded', 2)
                    if (typeof _this.pageRenderCallback == 'function') {
                        var cl = page.attr('class');
                        var reg = new RegExp("page *(.*?-page)", "i");
                        var r = cl.match(reg);
                        if (r != null) {
                            _this.pageRenderCallback(unescape(r[1]), result);
                        } else {
                            _this._log('pageRenderCallback fail,page:' + cl)
                        }
                    }
                } else {
                    page.data('loaded', 0);
                }
            })
        } catch (e) {
            page.data('loaded', 0);
            throw e;
        }

    },
    alert: function (txt, callback) {
        if ($('body').find('.confirm-box').length == 0) {
            $('body').append('<div class="confirm-box"><div class="confirm-content"><div class="txt">是否删除？</div><div class="btns"><div class="confirm-ok">知道了</div><div class="confirm-no">取消</div><div class="confirm-yes">确认</div></div></div></div>')
        }
        $('.confirm-box .confirm-yes').hide();
        $('.confirm-box .confirm-no').hide();
        $('.confirm-box .confirm-ok').show();
        $('.confirm-box .txt').html(txt)
        $('.confirm-box .confirm-ok').unbind();
        $('.confirm-box .confirm-ok').click(function () {
            $('.confirm-box .confirm-ok').unbind();
            $('.confirm-box').hide();
            typeof callback == 'function' && callback();
        })
        $('.confirm-box').show();
    },
    confirm: function (txt, callback) {
        if ($('body').find('.confirm-box').length == 0) {
            $('body').append('<div class="confirm-box"><div class="confirm-content"><div class="txt">是否删除？</div><div class="btns"><div class="confirm-ok">知道了</div><div class="confirm-no">取消</div><div class="confirm-yes">确认</div></div></div></div>')
        }
        $('.confirm-box .confirm-yes').show();
        $('.confirm-box .confirm-no').show();
        $('.confirm-box .confirm-ok').hide();
        $('.confirm-box .txt').html(txt)
        $('.confirm-box .confirm-yes').unbind();
        $('.confirm-box .confirm-no').unbind();
        $('.confirm-box .confirm-yes').click(function () {
            $('.confirm-box .confirm-yes').unbind();
            $('.confirm-box .confirm-no').unbind();
            $('.confirm-box').hide();
            typeof callback == 'function' && callback(true);
        })
        $('.confirm-box .confirm-no').click(function () {
            $('.confirm-box .confirm-yes').unbind();
            $('.confirm-box .confirm-no').unbind();
            $('.confirm-box').hide();
            typeof callback == 'function' && callback(false);
        })
        $('.confirm-box').show();
    },

    _dropListRender: function (miniRefresh, container, dataListDom, result, reflush) {
        var paginateKey = container.attr('_paginateKey'), paginateInfo = result;
        if (paginateKey) {
            paginateKey = paginateKey.split('.');
            for (var i in paginateKey) {
                paginateInfo = paginateInfo[paginateKey[i]]
            }
        }
        console.log(paginateInfo)
        if (result === false) {
            reflush ? miniRefresh.endDownLoading(true) : miniRefresh.endUpLoading(true);
        } else {
            container.data('next_url', paginateInfo.next_page_url)
            container.data('prev_url', paginateInfo.prev_page_url)
            try {
                var compiledTemplate = Template7.compile($('#' + container.attr('_tpl')).html());
                var html = compiledTemplate(result.data);
                if (reflush) {
                    dataListDom.html(html);
                } else {
                    dataListDom.append(html);
                }
            } catch (e) {
                console.log('render error:', e)
            }
            reflush ? miniRefresh.endDownLoading(true) : miniRefresh.endUpLoading(paginateInfo.next_page_url ? false : true);
        }
    },
    dropListInit: function (containerSelector, url, Up, Down, success) {
        var _this = this;
        Up = typeof Up == 'undefined' ? true : Up;
        Down = typeof Down == 'undefined' ? true : Down;

        var container = $(containerSelector);
        if (container.find('.minirefresh-scroll').length == 0) {
            container.html('<div class="minirefresh-scroll"><ul class="data-list"></ul></div>')
        }
        container.addClass('minirefresh-wrap')
        var dataListDom = container.find('.data-list')
        container.data('url', url)
        container.data('next_url', url)
        container.data('prev_url', '')

        var miniRefresh = null;
        var config = {
            container: containerSelector,
            up: {
                isLock: !Up,
                isAuto: true,
                callback: function () {
                    var next_url = container.data('next_url');
                    if (!next_url) {
                        miniRefresh.endUpLoading(true);
                        return;
                    }
                    var params = container.data('params');
                    if (typeof params != 'object') {
                        params = {};
                    }
                    for (var i in _this.request) {
                        params[i] = _this.request[i]
                    }

                    _this.getJson(next_url, params, function (result) {
                        if (result.errCode != 0) {
                            container.data('loaded', 0);
                            miniRefresh.endUpLoading(true)
                            return;
                        }
                        _this._dropListRender(miniRefresh, container, dataListDom, result, false);
                        success();
                    })
                }
            },
            down: {
                isLock: !Down,
                callback: function () {
                    var url = container.data('url');
                    var params = container.data('params');
                    if (typeof params != 'object') {
                        params = {};
                    }
                    for (var i in _this.request) {
                        params[i] = _this.request[i]
                    }
                    _this.getJson(url, params, function (result) {
                        if (result.errCode != 0) {
                            container.data('loaded', 0);
                            miniRefresh.endDownLoading(true)
                            return;
                        }
                        _this._dropListRender(miniRefresh, container, dataListDom, result, true)
                        success();
                    })
                }
            }
        };
        var miniRefresh = new MiniRefresh(config);
        return miniRefresh;
    },
}

function addressStr(value) {
    var province_code = value.substr(0, 2) + '0000';
    var city_code = value.substr(0, 4) + '00';
    var area_code = value;
    var name = '';
    var data = Js_Area;
    if (data && data[province_code]) {
        name += data[province_code].name;
        data = data[province_code].sub;
    }
    if (data && data[city_code]) {
        name += data[city_code].name;
        data = data[city_code].sub;
    }
    if (data && data[area_code]) {
        name += data[area_code].name;
        data = data[area_code].sub;
    }
    return name;
}
function addressInit(dom, value, level, lock) {
    lock = typeof lock == 'undefined' ? true : false;
    var name = dom.attr('data-name');
    if (typeof name == 'undefined') {
        console.log('address data-name is undefined!');
    }
    var province, city, area;
    var province_code, city_code, area_code;

    if (level == 1) {
        dom.append('<select name="' + name + '"></select>');
        province = $(dom.find('select')[0])
    } else if (level == 2) {
        dom.append('<select></select>');
        dom.append('<select name="' + name + '"></select>');
        province = $(dom.find('select')[0])
        city = $(dom.find('select')[1]);
    } else {
        dom.append('<select></select>');
        dom.append('<select></select>');
        dom.append('<select name="' + name + '"></select>');
        province = $(dom.find('select')[0])
        city = $(dom.find('select')[1]);
        area = $(dom.find('select')[2]);
    }

    if (typeof value == 'undefined' || value == '') {
        value = '000000';
    }
    province_code = value.substr(0, 2) + '0000';
    city_code = value.substr(0, 4) + '00';
    area_code = value;

    for (i in Js_Area) {
        if (Js_Area[i].code == province_code) {
            province.append('<option selected="selected" value="' + Js_Area[i].code + '">' + Js_Area[i].name + '</option>');
        } else {
            if (level == 1 || !lock)
                province.append('<option value="' + Js_Area[i].code + '">' + Js_Area[i].name + '</option>');
        }
    }
    if (level > 1) {
        province.change(function () {
            var code = $(this).val();
            var sub = Js_Area[code].sub;
            city.empty();
            for (i in sub) {
                city.append('<option value="' + sub[i].code + '">' + sub[i].name + '</option>');
            }
            city.change();
        });
    }
    if (level > 2) {
        city.change(function () {
            var code = $(this).val();
            var parent_code = code.substr(0, 2) + '0000';
            var sub = Js_Area[parent_code].sub[code].sub;
            //console.log(sub);
            area.empty();
            for (i in sub) {
                area.append('<option value="' + sub[i].code + '">' + sub[i].name + '</option>');
            }
        });
    }
    province.change();
    if (level == 2) {
        if (city_code != '000000') {
            city.val(city_code);
        }
    }
    if (level == 3) {
        city.change();
        if (area_code != '000000') {
            area.val(area_code);
        }
    }
}
Template7.registerHelper('addressStr', function (area_code) {
    return addressStr(area_code);
});
Template7.registerHelper('console', function (a) {
    return JSON.stringify(a);
})
Template7.registerHelper('short_day', function (a) {
    return a && a.substr(0, 10);
})