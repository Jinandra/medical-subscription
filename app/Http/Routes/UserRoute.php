<?php



  Route::get('/auth/login',function () {
    return Redirect::to('/?modal=login');
  });

  // user
  Route::group(['prefix' => 'user', 'as' => 'user::'], function () {
    $CONTROLLER = 'UserController';
    // login
    Route::get("activation",    ['as' => 'activation', 'uses' => "$CONTROLLER@activation"]);
    Route::get("registration",  ['as' => 'registration', 'uses' => "$CONTROLLER@registration"]);
    Route::post("registration", ['as' => 'register', 'uses' => "$CONTROLLER@register"]);

    // session
    Route::get("login",   ['as' => 'login', 'uses' => "$CONTROLLER@loginForm"]);
    Route::post("login",  ['as' => 'doLogin', 'uses' => "$CONTROLLER@login"]);

    Route::group(['middleware' => ['auth','user.status']], function () use ($CONTROLLER) {
      Route::post("changeprofilepassword",  ['uses' => "$CONTROLLER@changeProfilePassword"]);
      Route::get("accountverifyoldpassword",['uses' => "$CONTROLLER@accountVerifyOldPassword"]);
      Route::get("logout",                  ['as' => 'logout', 'uses' => "$CONTROLLER@logout"]);
    });
  });

  // account
  Route::group(['prefix' => 'account', 'middleware' => ['auth','user.status']], function () {
    $CONTROLLER = 'UserController';
    Route::get("/",  ['as' => 'index',  'uses' => "$CONTROLLER@account"]);
    Route::post("/", ['as' => 'update', 'uses' => "$CONTROLLER@account"]);
  });

  // Admin user
  // admin
  Route::group([
      'prefix' => 'admin',
      'as' => 'admin::',
      'middleware' => ['auth','role:user', 'role:administrator']],
      function () {

    $CONTROLLER = 'Admin\UserController';
    Route::get('/', ['as' => 'dashboard', 'uses' => "$CONTROLLER@dashboard"]);

    // admin/user
    Route::group(['prefix' => 'user', 'as' => 'user::'], function () use ($CONTROLLER) {
      Route::get('change-password',  ['uses' => "$CONTROLLER@changePassword"]);
      Route::post('change-password', ['uses' => "$CONTROLLER@changePassword"]);
      Route::get('new',              ['as' => 'new', 'uses' => "$CONTROLLER@addNew"]);
      Route::get('view',             ['uses' => "$CONTROLLER@viewUser"]);
      Route::get('ucodes',           ['as' => 'ucodes', 'uses' => "$CONTROLLER@ucodes"]);
      Route::get('{id}/ucodes',      ['as' => 'userUcodes', 'uses' => "$CONTROLLER@detailUcodes"]);
      Route::get('ucode/{ucode}',    ['uses' => "$CONTROLLER@viewUCodeMedia"]);
      Route::get('/pendings',        ['as' => 'pendings', 'uses' => "$CONTROLLER@pendingVerification"]);
      Route::post('{id}/verify',     ['uses' => "$CONTROLLER@verify"]);
      Route::get('{id}',             ['uses' => "$CONTROLLER@show"]);
      Route::delete('/{id}',         ['as' => 'delete', 'uses' => "$CONTROLLER@delete"]);
      Route::get('/',                ['as' => 'index', 'uses' => "$CONTROLLER@index"]);
      Route::post('/',               ['as' => 'create', 'uses' => "$CONTROLLER@create"]);
      Route::patch('/{id}',               ['as' => 'patch', 'uses' => "$CONTROLLER@patch"]);
    });

  });
