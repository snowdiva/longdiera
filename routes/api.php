<?php

use Illuminate\Http\Request;

Route::group(['namespace' => 'Admin', 'prefix' => 'admin_api'], function () {
    // 登录及获取用户信息
    Route::post('/login', 'UserController@getInfo');

    Route::group(['middleware' => 'adminApiToken'], function () {
        // 用户操作
        Route::post('/users', 'UserController@getUserList');
        Route::post('/users/new', 'UserController@newUser');
        Route::post('/users/edit', 'UserController@editUser');
        Route::post('/users/delete', 'UserController@deleteUser');
    });
});


