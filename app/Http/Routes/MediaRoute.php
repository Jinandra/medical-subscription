<?php

Route::group(['prefix' => 'media', 'as' => 'media::', 'middleware' => ['auth']], function () {
  $CONTROLLER = "MediaController";
  Route::get('add-to-folder/{media_id}/{folder_id}', 'MediaController@addToFolder');
  Route::post('bulk-send-folder', 'MediaController@bulkSendFolder');
  Route::post('report', 'MediaController@sendReport');
  Route::get('search', ['as' => 'search', 'uses' => "{$CONTROLLER}@search"]);
  Route::get('{id}/like', 'MediaController@like');
  Route::get('{id}/dislike', 'MediaController@dislike');
  Route::get('{id}/folder', 'MediaController@listFolder');
  Route::get('{id}', ['as' => 'show', 'uses' => "{$CONTROLLER}@item"]);
});
Route::get('/media-document/{id}', 'MediaController@showDocument');
Route::get('/media/ajax/{id}', 'MediaController@itemAjax');


Route::group(['prefix' => 'admin', 'as' => 'admin::', 'middleware' => ['auth', 'user.status', 'role:administrator']], function () {
  Route::group(['prefix' => 'media', 'as' => 'media::'], function () {
    $CONTROLLER = "Admin\MediaController";
    Route::get('make-private/{id}', ['uses' => "{$CONTROLLER}@makePrivate"]);
    Route::get('make-public/{id}',  ['uses' => "{$CONTROLLER}@makePublic"]);
    Route::get('delete/{id}', ['uses' => "{$CONTROLLER}@delete"]);
    Route::get('ban/{id}',    ['uses' => "{$CONTROLLER}@ban"]);
    Route::get('reports',     ['uses' => "{$CONTROLLER}@showReports"]);
    Route::get('report/delete/{id}',  ['uses' => "{$CONTROLLER}@deleteReport"]);
    Route::get('report/show/{id}',    ['uses' => "{$CONTROLLER}@showReport"]);

    Route::get('/',         ['as' => 'index', 'uses' => "{$CONTROLLER}@index"]);
    Route::delete('/{id}',  ['as' => 'delete', 'uses' => "{$CONTROLLER}@delete"]);
  });
});
