<?php

use Illuminate\Http\Request;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:api');

Route::group(['domain' => 'local.api.snowdiva.cn'], function () {
    Route::get('/', 'HomeController@index');
});


