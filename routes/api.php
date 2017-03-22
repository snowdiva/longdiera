<?php

use Illuminate\Http\Request;

Route::group(['namespace' => 'Admin', 'prefix' => 'admin_api'], function () {
    // 登录及获取用户信息
    Route::post('/login', 'UserController@getInfo');

    Route::group(['middleware' => 'adminApiToken'], function () {
        // 欢迎界面
        Route::post('/home', 'HomeController@getDashboard');
        // 用户操作
        Route::post('/users', 'UserController@getUserList');
        Route::post('/users/new', 'UserController@newUser');
        Route::post('/users/edit', 'UserController@editUser');
        Route::post('/users/delete', 'UserController@deleteUser');
        // 用户组操作
        Route::post('/group', 'GroupController@getGroupList');
        Route::post('/group/new', 'GroupController@newGroup');
        Route::post('/group/edit', 'GroupController@editGroup');
        Route::post('/group/delete', 'GroupController@deleteGroup');
        // 权限操作
        Route::post('/auth', 'AuthorityController@getAuthList');
        Route::post('/auth/new', 'AuthorityController@newAuth');
        Route::post('/auth/edit', 'AuthorityController@editAuth');
        Route::post('/auth/delete', 'AuthorityController@deleteAuth');
    });
});


