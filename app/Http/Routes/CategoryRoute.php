<?php

Route::group(['prefix' => 'admin', 'as' => 'admin::', 'middleware' => ['auth', 'role:administrator']], function () {

  Route::group(['prefix' => 'categories', 'as' => 'categories::'], function () {
    $CONTROLLER = 'Admin\CategoryController';
    Route::get('/',           ['as' => 'index',   'uses' => "{$CONTROLLER}@index"]);
    Route::get('/new',        ['as' => 'new',     'uses' => "{$CONTROLLER}@addNew"]);
    Route::post('/',          ['as' => 'create',  'uses' => "{$CONTROLLER}@store"]);

    Route::get('/{id}/media',   ['as' => 'media', 'uses' => "{$CONTROLLER}@media"]);
    Route::post('/{id}/media',  ['as' => 'createMedia', 'uses' => "{$CONTROLLER}@storeMedia"]);
    Route::patch('/{id}/media', ['as' => 'patchMedia',  'uses' => "{$CONTROLLER}@updateMedia"]);
    Route::delete('/{categoryId}/media/{mediaId}', ['as' => 'deleteMedia', 'uses' => "{$CONTROLLER}@deleteMedia"]);

    Route::get('/{id}/edit',  ['as' => 'edit',    'uses' => "{$CONTROLLER}@edit"]);
    Route::patch('/{id}',     ['as' => 'patch',   'uses' => "{$CONTROLLER}@update"]);
    Route::delete('/{id}',    ['as' => 'delete',  'uses' => "{$CONTROLLER}@delete"]);

  });

});
