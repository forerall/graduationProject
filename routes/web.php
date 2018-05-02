<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//第三方回调
Route::any('/wxMsgCallback', 'WxController@wxMsgCallback');//微信消息推送
Route::any('/wxpayCallback', 'Api\PayCallbackController@wxpayCallback');//微信支付
Route::any('/alipayCallback', 'Api\PayCallbackController@alipayCallback');//微信支付


/**
 * 身份验证路由
 */
Auth::routes();
Route::get('/backstage/login', 'Auth\BackstageLoginController@showLoginForm');
Route::post('/backstage/login', 'Auth\BackstageLoginController@login');
Route::post('/backstage/logout', 'Auth\BackstageLoginController@logout');

/**
 * 所有图片上传
 */
Route::post('/upload', 'CommonController@upload');
Route::post('/backstage/userdown', 'Backstage\UserController@userdown');
Route::post('/backstage/userup', 'Backstage\UserController@userup');
/**
 * 后台路由
 */
Route::group([
    'prefix' => 'backstage',
    'namespace' => 'Backstage',
    'middleware' => 'backstage_auth:admin'//后台使用admin验证
], function () {
    Route::get('/', 'HomeController@dashboard');
    Route::get('dashboard', 'HomeController@dashboard');

    //系统设置
    Route::resource('room', 'RoomController');//
    Route::post('roomState', 'RoomController@roomState');//
    Route::resource('admin', 'AdminController');//管理员
    Route::resource('article', 'ArticleController');//文章页面
    Route::resource('setting', 'SettingController');//参数设置
    Route::resource('proxy', 'ProxyController');
    Route::post('openProxy', 'ProxyController@openProxy');
    Route::post('closeProxy', 'ProxyController@closeProxy');
    Route::post('jingyong/{id}', 'UserController@jingyong');
    Route::get('proxyList', 'ProxyController@proxyList');
    Route::get('tongji', 'ProxyController@tongji');
    Route::get('chou', 'HomeController@chou');

    Route::get('balance', 'HomeController@balance');
    Route::get('listfa', 'HomeController@listfa');
    Route::get('bz', 'HomeController@bz');
    Route::get('clearTips', 'HomeController@clearTips');
    Route::get('tips', 'HomeController@tips');

    Route::match(['get','post'],'payWx', 'HomeController@payWx');
    Route::match(['get','post'],'payAli', 'HomeController@payAli');
    Route::match(['get','post'],'kf', 'HomeController@kf');
    Route::match(['get','post'],'fa', 'HomeController@fa');
    Route::match(['get','post'],'cs', 'HomeController@cs');
    Route::match(['get','post'],'zb', 'HomeController@zb');

    Route::resource('user', 'UserController');
    Route::resource('withdraw', 'WithdrawController');
    Route::resource('recharge', 'RechargeController');
    Route::post('rechargeConfirm', 'RechargeController@rechargeConfirm');
    Route::post('withdrawConfirm', 'WithdrawController@withdrawConfirm');
    Route::post('fabz', 'HomeController@fabz');





});

/**
 * 前台路由
 */
Route::group([
    'namespace' => 'Home'
], function () {

    Route::group([
        'middleware' => ['wechat', 'auth']  //wechat 自动登录微信
    ], function () {
        Route::get('/', 'HomeController@index');
        Route::get('/pass', 'HomeController@pass');
        Route::get('/roomList', 'HomeController@roomList');
        Route::get('/roomList2', 'HomeController@roomList2');
        Route::get('/user', 'HomeController@user');
        Route::get('/roomPage', 'HomeController@roomPage');
        Route::get('/checkGetPacketStatus', 'HomeController@checkGetPacketStatus');
        Route::get('/getPacket', 'HomeController@getPacket');
        Route::get('/packetRecord', 'HomeController@packetRecord');
        Route::post('/sendPacket', 'HomeController@sendPacket');
        Route::get('/kf', 'HomeController@kf');
        Route::get('/balanceLog', 'HomeController@balanceLog');
        Route::get('/proxy', 'HomeController@proxy');
        Route::get('/tongji', 'HomeController@tongji');
        Route::get('/gz', 'HomeController@gz');
        Route::match(['get', 'post'], '/up', 'HomeController@up');
        Route::match(['get', 'post'], '/down', 'HomeController@down');
        Route::get('openProxy', 'HomeController@openProxy');

        Route::match(['get', 'post'], '/lq', 'HomeController@lq');
    });
});