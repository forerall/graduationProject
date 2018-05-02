<?php
/**
 * Created by PhpStorm.
 * User: ygy
 * Date: 2017/6/7
 * Time: 9:59
 */

//缓存数据
$value = Cache::remember('users', $minutes, function () {
    return DB::table('users')->get();
});



//添加缓存，$minutes分钟后过期
Cache::put('key', 'value', $minutes);

//添加缓存，$expiresAt后过期
$expiresAt = Carbon::now()->addMinutes(10);
Cache::put('key', 'value', $expiresAt);
cache(['key' => 'value'], $minutes);

//添加缓存，当key不存在是添加，成功返回true，失败返回false
Cache::add('key', 'value', $minutes);
cache(['key' => 'value'], Carbon::now()->addSeconds(10));

//添加缓存，不过期
Cache::forever('key', 'value');





//修改
Cache::increment('key');
Cache::increment('key', $amount);
Cache::decrement('key');
Cache::decrement('key', $amount);


//获取，不存在返回null
$value = Cache::get('key');
$value = Cache::get('key', 'default');
cache('key');

//选择缓存引擎
Cache::store('file')->get('foo');

//删除缓存
Cache::forget('key');
//删除全部缓存
Cache::flush();


//获取key并删除缓存，不存在返回null
$value = Cache::pull('key');