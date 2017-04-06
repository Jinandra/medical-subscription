<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */

$route_partials = [
    'UserRoute',
    'PostRoute',
    'CollectionRoute',
    'BundleRoute',
    'ForgotPasswordRoute',
    'LearnRoute',
    'MediaRoute',
    'CategoryRoute',
    'BasicCollectionRoute'
];

/** Route Partial Loadup
  =================================================== */
foreach ($route_partials as $partial) {

    $file = __DIR__ . '/Routes/' . $partial . '.php';

    if (!file_exists($file)) {
        $msg = "Route partial [{$partial}] not found.";
        throw new \Illuminate\Filesystem\FileNotFoundException($msg);
    }

    require_once $file;
}
Route::get('/', 'SiteController@index')->middleware('isUserLogin');
Route::get('/daily', 'SiteController@daily');
Route::get('/weekly', 'SiteController@weekly');
Route::get('/monthly', 'SiteController@monthly');
Route::get('/newly', 'SiteController@newly');

Route::get('/search', 'HomeController@search')->middleware('auth');
Route::get('/search/filter', 'HomeController@searchByFilters')->middleware('auth');
Route::post('/search/filter', 'HomeController@searchByFiltersViaAjax')->middleware('auth');

Route::get('/ucode/{ucode}', 'HomeController@singleUcode');

Route::get('/user', 'HomeController@index')->middleware('auth');

Route::get('/profile', function () {
    return view('prototype.profile');
})->middleware('auth');

Route::get('/contribute/addForm', 'MediaController@addForm')->middleware('auth');
Route::get('/contribute/{type?}', 'MediaController@index')->middleware('auth');
Route::post('/contribute/add', 'MediaController@store')->middleware('auth');
Route::get('/contribute/{id}/edit', 'MediaController@edit')->middleware('auth');
Route::delete('/contribute/{id}', 'MediaController@delete')->middleware('auth');
Route::post('/contribute/{id}/update', 'MediaController@update')->middleware('auth');
Route::post('/contribute/sendToFolder', 'MediaController@sendToFolder')->middleware('auth');

Route::get('/user/{id}/fav', 'HomeController@fav')->middleware('auth');



/* Move to routes/userRoute.php
  Route::get('/account',function () {
  return view('prototype.account');
  });
 */

Route::get('/aboutus', function () {
    return view('prototype.aboutus');
});

Route::controller('sandbox', 'SandboxController');

