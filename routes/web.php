<?php

// 前台访问入口
Route::get('/', 'HomeController@index');

// 后台访问入口
Route::get('/webmaster', 'AdminController@index');