<?php
  Route::get('/learns', ['uses' => 'HomeController@learn'])->middleware('auth');

  Route::group(['prefix' => 'admin','middleware' => ['auth', 'user.status', 'role:administrator']], function () {
    Route::get('/learns', ['uses' => 'LearnController@index']);
    Route::get('/learns/new', ['uses' => 'LearnController@addNew']);
    Route::post('/learns', ['uses' => 'LearnController@create']);
    Route::post('/learns/{id}/update', ['uses' => 'LearnController@update']);
    Route::get('/learns/delete', ['uses' => 'LearnController@delete']);
    Route::get('/learns/edit', ['uses' => 'LearnController@edit']);
  });
