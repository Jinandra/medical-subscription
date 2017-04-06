<?php

const CONTROLLER = 'Auth\PasswordController';

Route::get('/password/reset', CONTROLLER.'@getEmail');
Route::post('/password/email', CONTROLLER.'@postEmail');
Route::get('/password/reset/{token}', CONTROLLER.'@getReset');
Route::post('/password/reset', CONTROLLER.'@postReset');
