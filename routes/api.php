<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/upload', 'CommonController@upload');

Route::group([
    'namespace' => 'Api',
], function () {
    Route::group([
        'middleware'=>'api_auth_sometime'
    ],function() {
        //未登录接口
        Route::get('/regProtocol', 'LoginController@regProtocol');//注册协议
        Route::get('/sjProtocol', 'LoginController@sjProtocol');//注册协议
        Route::get('/spProtocol', 'LoginController@spProtocol');//注册协议
        Route::get('/about', 'LoginController@about');//关于我们
        Route::get('/fwbz', 'LoginController@fwbz');//服务保证
        Route::get('/smlc', 'LoginController@smlc');//上面流程




        Route::post('/register', 'LoginController@register');
        Route::post('/login', 'LoginController@loginByPassword');
        Route::post('/resetPassword', 'LoginController@resetPassword');

        Route::post('/sendRegisterCode', 'LoginController@sendRegisterCode');
        Route::post('/sendForgetPasswordCode', 'LoginController@sendForgetPasswordCode');
        Route::post('/sendChangePhoneCode', 'LoginController@sendChangePhoneCode');
        Route::post('/feedback', 'LoginController@feedback');//反馈


        Route::get('pai','PaiController@index');
        Route::get('pai/{id}','PaiController@show');

        Route::get('store','StoreController@index');
        Route::get('store/{id}','StoreController@show');
        Route::get('buildingList','StoreController@buildingList');//大楼店铺列表

        Route::get('product','ProductController@index');
        Route::get('product/{id}','ProductController@show');


    });


    Route::group([
        'middleware'=>'api_auth'
    ],function(){
        //已登录接口
        Route::post('/changePhone', 'LoginController@changePhone');
        Route::post('/modifyPassword', 'LoginController@modifyPassword');
        //个人中心
        Route::get('userRefresh','UserController@userRefresh');
        Route::post('userEditField','UserController@editField');
        Route::resource('auth','AuthController');
        Route::resource('balance','BalanceController');
        Route::resource('withdraw','WithdrawController');
        Route::resource('collect','CollectController');

        //拍拍
        Route::post('pai','PaiController@store');
        Route::delete('pai/{id}','PaiController@destroy');
        Route::post('paiLike','PaiController@like');
        Route::post('paiAddComment','PaiController@addComment');
        Route::post('paiReward','PaiController@reward');
        Route::get('unreadList','PaiController@unreadList');//未读评论列表
        Route::get('readComment','PaiController@readComment');//标记已读
        //
        Route::get('tip','PaiController@tip');//提醒数量

        //店铺管理
        Route::post('store','StoreController@store');
        Route::post('storeEditField','StoreController@editField');
        Route::post('addAuth','StoreController@addAuth');//店铺认证
        //商品
        Route::post('product','ProductController@store');
        Route::put('product/{id}','ProductController@update');
        Route::delete('product/{id}','ProductController@destroy');
        Route::post('productLike','ProductController@like');
        Route::post('productCollect','ProductController@collect');

        //地址管理
        Route::resource('address','AddressController');


        //聊天
        Route::get('getImages','ImController@getImages');//获取表情
        Route::post('addImage','ImController@addImage');//添加表情
        Route::get('sendMessageTip','ImController@sendMessageTip');//
        Route::get('getUserInfo','ImController@getUserInfo');//获取用户信息列表
        Route::post('sendMoney','ImController@sendMoney');//发红包
        Route::post('recMoney','ImController@recMoney');//收红包
        Route::post('addBeizhu','ImController@addBeizhu');//设置备注


        //订单
        Route::resource('/order','OrderController');//订单
        Route::get('preStore','OrderController@preStore');//下单页面
        Route::post('/cancelOrder','OrderController@cancelOrder');//订单
        Route::post('/sendOrder','OrderController@sendOrder');//订单
        Route::post('/confirmOrder','OrderController@confirmOrder');//订单
        Route::post('/testPay','OrderController@testPay');//订单
        Route::get('/getPayStr','OrderController@getPayStr');//订单

        //充值
        Route::resource('/charge','RechargeController');//订单

        //首页
        Route::get('/getAround','MapController@getAround');//地图覆盖物




        Route::get('/userRefresh','UserController@userRefresh');//刷新用户信息
        Route::get('/userDetail','UserController@userDetail');//刷新用户信息
        Route::post('/addComment','CommentController@addComment');//发表评价
        Route::post('/editField','UserController@editField');//用户信息编辑
        Route::resource('/auth','AuthController');//认证
        Route::resource('/withdraw','WithdrawController');//提现
        Route::resource('/balance','BalanceController');//钱包
        Route::resource('/collect','CollectController');//收藏
        Route::post('/addCollect','CollectController@addCollect');//收藏

    });
});
