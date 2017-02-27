<?php

Route::get('/', 'HomeController@index');
Route::get('/novel/{id}', 'NovelController@index');
Route::get('/test', function () {
    return 'test';
});

Route::get('/init_admin', 'TestController@initAdmin');

Route::group(['namespace' => 'Admin', 'prefix' => 'webmaster'], function () {
    Route::get('/login', 'UserController@login')->name('adminLogin');
    Route::post('/login', 'UserController@loginPost');
    Route::get('/logout', 'UserController@logout')->name('adminLogout');

//    Route::get('/collect_test', 'CollectController@collect_test');

    // 需要CSRF验证的访问
    Route::group(['middleware' => ['admin.auth', 'csrf']], function () {
        Route::get('dashboard', 'HomeController@dashboard')->name('dashboard');
        Route::get('/', 'HomeController@dashboard');

        Route::get('/user/index', 'UserController@index')->name('user_index');
        Route::get('/group/index', 'GroupController@index')->name('group_index');
        Route::get('/auth/index', 'AuthorityController@index')->name('auth_index');
        Route::get('/auth/add', 'AuthorityController@authAdd');
        Route::post('/auth/add', 'AuthorityController@authAddPost');
        Route::get('/auth/edit/{id}', 'AuthorityController@authEdit');
        Route::post('/auth/edit', 'AuthorityController@authEditPost');
        Route::get('/auth/bind/{user_id}', 'AuthorityController@authBind');
        Route::post('/auth/bind', 'AuthorityController@authBindPost');

        Route::get('/article/index', 'ArticleController@index')->name('article_index');
        Route::get('/article/add', 'ArticleController@articleAdd');
        Route::post('/article/add', 'ArticleController@articleAddPost');
        Route::get('/article/edit/', 'ArticleController@articleEdit');
        Route::post('/article/edit', 'ArticleController@articleEditPost');
        Route::get('/article/sort', 'ArticleController@sort')->name('sort');
        Route::get('/article/sort_add', 'ArticleController@sortAdd');
        Route::post('/article/sort_add', 'ArticleController@sortAddPost');
        Route::get('/article/sort_edit', 'ArticleController@sortEdit');
        Route::post('/article/sort_edit', 'ArticleController@sortEditPost');
        Route::get('/collect', 'CollectController@collect')->name('collect');
        Route::get('/collect/get', 'CollectController@collectGet')->name('collect_get');
        Route::get('/collect/add', 'CollectController@collectAdd');
        Route::post('/collect/add', 'CollectController@collectAddPost');
        Route::get('/chapter/index', 'ChapterController@index')->name('chapter_index');
        Route::get('/chapter/add', 'ChapterController@add');
        Route::post('/chapter/add', 'ChapterController@addPost');
        Route::get('/chapter/edit/', 'ChapterController@edit');
        Route::post('/chapter/edit', 'ChapterController@editPost');
        Route::post('/chapter/delete', 'ChapterController@delete');
        Route::post('/chapter/audit', 'ChapterController@audit');

        Route::get('/recommend', 'RecommendController@index')->name('recommend');
        Route::get('/recommend/add', 'RecommendController@recommendAdd');
        Route::post('/recommend/add', 'RecommendController@recommendAddPost');
        Route::get('/recommend/edit', 'RecommendController@recommendEdit');
        Route::post('/recommend/edit', 'RecommendController@recommendEditPost');
        Route::get('/recommend/group', 'RecommendController@groupIndex')->name('recommend_group');
        Route::get('/recommend/group/add', 'RecommendController@groupAdd');
        Route::post('/recommend/group/add', 'RecommendController@groupAddPost');
        Route::get('/recommend/group/edit', 'RecommendController@groupEdit');
        Route::post('/recommend/group/edit', 'RecommendController@groupEditPost');

        Route::get('/hot', 'HotController@index')->name('hot_index');
        Route::get('/hot/add', 'HotController@hotAdd');
        Route::post('/hot/add', 'HotController@hotAddPost');
        Route::get('/hot/edit', 'HotController@hotEdit');
        Route::post('/hot/edit', 'HotController@hotEdit/Post');
    });

    // 不需要CSRF验证的访问
    Route::group(['middleware' => 'admin.auth'], function () {
        Route::post('/upload/save_image', 'UploadController@save_image')->name('save_image');
        Route::post('/upload/cover_upload', 'UploadController@cover');
        Route::post('/article/audit', 'ArticleController@articleAudit');
        Route::post('/chapter/audit', 'ChapterController@audit');
    });
});