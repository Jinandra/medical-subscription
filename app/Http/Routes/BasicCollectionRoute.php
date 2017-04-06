<?php

Route::group(['prefix' => 'admin','middleware' => ['auth', 'user.status', 'role:administrator']], function () {
  Route::get('basiccollection',     ['uses' => 'Admin\BasicCollectionController@index']);
  Route::post('basiccollection',    ['uses' => 'Admin\BasicCollectionController@store']);
  Route::patch('basiccollection',   ['uses' => 'Admin\BasicCollectionController@update']);
  Route::delete('basiccollection/{id}', ['uses' => 'Admin\BasicCollectionController@delete']);
});
