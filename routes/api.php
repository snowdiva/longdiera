<?php

use Illuminate\Http\Request;

Route::group(['namespace' => 'Admin', 'prefix' => 'admin_api'], function () {
    // 登录及获取用户信息
    Route::post('/login', 'UserController@getInfo');

    // 用户列表操作
    Route::group(['middleware' => 'adminApiToken'], function () {
        Route::post('/users', 'UserController@getUserList');
    });
});


