<?php 
    
    Route::get('/post',         ['uses' => 'PostController@firstPost']);
    Route::get('/post/{slug}',  ['uses' => 'PostController@singlePost']);

    // Admin Area : Post
    Route::group(['prefix' => 'admin','middleware' => ['auth','user.status','role:administrator']], function () {    
        Route::get('/post',                      ['uses' => 'PostController@allPost']);
        Route::get('/post/add-new',              ['uses' => 'PostController@addNew']);
        Route::post('/post/add-new',             ['uses' => 'PostController@addNew']);
        Route::get('/post/edit',                 ['uses' => 'PostController@editPost']);
        Route::post('/post/edit',                ['uses' => 'PostController@editPost']);
        Route::get('/post/delete',               ['uses' => 'PostController@deletePost']);
        Route::patch('/post',                    ['uses' => 'PostController@patchPosts']);
    });
