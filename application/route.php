<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

/**
 * 用户信息展示
 */
Route::get('user','user/user/index');

/**
 * 信息展示
 */
Route::get('message/info','message/message/info');
Route::get('message','message/message/index');

/**
 * api 路由器
 */
Route::get('api/message/getUnreadData','api/message/getUnreadData');
Route::post('api/message/store','api/message/store');
Route::get('api/message/getInfo','api/message/getInfo');
Route::get('api/message/listData','api/message/listData');
Route::put('api/message/readData','api/message/readData');

//return [
//    '__pattern__' => [
//        'name' => '\w+',
//    ],
//    '[hello]'     => [
//        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//        ':name' => ['index/hello', ['method' => 'post']],
//    ],
//
//];
