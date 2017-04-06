<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Illuminate\Session\TokenMismatchException;
use App\Models\User;
use App\Models\Role;
use App\Models\Ucode;
use App\Models\Media;
use App\Models\BundleCart;
use App\Models\Category;
use App\Models\UserCategory;
use App\Models\Collection;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Input,
    Validator,
    Redirect,
    Hash,
    DB,
    Auth,
    Mail,
    File,
    Form;



class UserController extends Controller {

  /**
   * GET /user/registration
   * user::registration
   * Show user registration form
   */
  public function registration () {
    return view('beta.user.registration', [
      'google_site_key' => config('google.SITE_KEY'),
      'categories' => Category::ordered()->get()
    ]);
  }

  /**
   * POST /user/registration
   * user::register
   * Register new user from website frontend
   */
  public function register (Request $request) {
    $rules = [
      'screen_name' => 'required|min:3|max:100|alpha_dash|unique:users,screen_name',
      'password' => 'required|min:6|max:30|same:password_confirmation',
      'password_confirmation' => 'required|min:6|max:30',
      'email' => 'email|max:255|unique:users,email',
      'first_name' => 'required',
      'last_name' => 'required',
      'categories' => 'array|required',
      'medical_profession' => 'required',
      'medical_degree' => 'required',
      'office_address' => 'required'
    ];
    $messages = [
      'categories.required' => 'At least one of Medical Field of Interest must be selected.'
    ];
    $validator = Validator::make(Input::all(), $rules, $messages);
    $recaptchaResponse = $this->_isRecaptchaSuccess($request->input('g-recaptcha-response'));

    if ( $validator->fails() || !$recaptchaResponse['success'] ) {
      return Redirect::to('/user/registration')
                      ->with('recaptcha', $recaptchaResponse['success'])
                      ->withErrors($validator)
                      ->withInput();
    }

    $user = DB::transaction(function() {
      $u = new User;
      $u->screen_name = Input::get('screen_name');
      $u->email = Input::get('email');
      $u->password = Hash::make(Input::get('password'));
      $u->first_name = Input::get('first_name');
      $u->last_name = Input::get('last_name');
      $u->medical_profession = Input::get('medical_profession');
      $u->medical_degree = Input::get('medical_degree');
      $u->office_address = Input::get('office_address');
      $u->website_type = Input::get('website_type');
      $u->profile_website_url = Input::get('profile_website_url');
      $u->website_url = Input::get('website_url');
      $u->save();

      $u->medicalInterests()->attach(array_keys(Input::get('categories')));
      
      return $u;
    });


    // Generate registration token
    $user->deleteToken('register');
    $token = $user->createToken('register', 180, 100); // 180 minutes (3jam), 100 characters.
    $user->register_token = $token->token;
    $user->save();

    $mail = Mail::send('emails.activation', ['user' => $user], function ($m) use ($user) {
      $m->from(config('app.EMAIL_ADDRESS_FROM'), config('app.EMAIL_SUBJECT_FROM'));
      $m->to($user->email, $user->fullname())->subject('Account Activation!');
    });

    return Redirect::to('user/activation');
  }

  /**
   * GET /user/login
   * Show login form
   */
  public function loginForm () {
    return view('beta.user.login');
  }

  /**
   * POST /user/login
   * Authenticate user login
   */
  public function login (Request $request) {
    $rules = [
        'screen_name' => 'required|min:3|max:100',
        'password' => 'required|min:6|max:30'
    ];

    $validator = Validator::make(Input::all(), $rules);

    $failRedirectUrl = "/?modal=login";
    if (preg_match("/\/user\/login/", $_SERVER['HTTP_REFERER']) === 1) {
      $failRedirectUrl = "/user/login";
    }

    Session::reflash();
    if ($validator->fails()) {
      Session::flash('status', 'fail');
      return Redirect::to($failRedirectUrl)
        ->withErrors($validator)
        ->with('flag', 'login')
        ->withInput();
    } else {
      $authParams = ['password' => Input::get('password'), 'user_status' => 'active'];
      $screenName = Input::get('screen_name');
      $success    = false;
      $remember   = is_null(Input::get('remember')) ? false : true;
      if ( Auth::attempt(array_merge($authParams, ['screen_name' => $screenName]), $remember) ) {
        $success = true;
      } else if ( Auth::attempt(array_merge($authParams, ['email' => $screenName]), $remember) ) {
        $success = true;
      }

      if ($success) {
        Session::flash('status', 'success');
        return Redirect::to('user');
      } else {
        Session::flash('status', 'fail');
        return Redirect::to($failRedirectUrl)
          ->with('flag', 'login')
          ->with('error_auth', 'Invalid Password.')
          ->withInput();
      }
    }
  }


  /**
   * GET /logout
   * Destroy user sessions
   */
  public function logout () {
    Auth::user()->emptyBundleCart();
    Auth::logout();
    return Redirect::to('/');
  }

  /**
   * GET /user/activation?[token=&user=]
   * Activate user registration by token, or show how to activate it
   */
  public function activation () {
    $withToken = Input::has('token') && Input::has('user');
    $data = array(
      'withToken' => $withToken,
      'activated' => false,
    );
    if ($withToken) {
      $user = User::find(Input::get('user'));
      if ($user->checkToken(Input::get('token'))) {
        $user->user_status = 'need_verification';
        $user->save();
        $user->syncMedicalInterestCollections();
        $data['activated'] = true;

        Mail::send('emails.requestVerification', ['user' => $user], function ($m) use ($user) {
          $m->from(config('app.EMAIL_ADDRESS_FROM'), config('app.EMAIL_SUBJECT_FROM'));
          $m->to(config('app.REVIEWER_EMAIL'), config('app.REVIEWER_NAME'))->subject("{$user->fullnameWithScreenName()} is requesting to create an account on Enfolink");
        });
      } else {
        $data['message'] = "Your token is invalid or already expired.";
      }
    }

    return view('beta.user.activation', $data);
  }

  /**
   * {GET|POST} /account
   * Show/edit user profile
   */
  public function account(Request $request) {
      $countBundleCart = BundleCart::getBundleCartCount(Auth::user()->id);
      $data['user'] = user::find(Auth::user()->id);
      $data['category'] = UserCategory::userCategoryData(Auth::user()->id);
      $usercatdata = array();
      
      // Update User Account
      if ((Input::get('submit')) && (Input::get('_token') != '')) {

          $rules = [
              'screen_name' => 'required|min:3|max:100|alpha_dash|unique:users,screen_name,' . Input::get('id'),
          ];

          if (Input::get('new_password') != '') {
              $rules = [
                  'new_password' => 'required|min:6|max:30|same:new_password_confirm',
                  'new_password_confirm' => 'required|min:6|max:30',
              ];
          }

          if (Input::get('new_email') != '') {
              $rules = [
                  'new_email' => 'email|max:255|unique:users,email,' . Input::get('id'),
              ];
          }

          $validator = Validator::make(Input::all(), $rules);

          if ($validator->fails()) {
              return Redirect::to('/account')
                              ->withErrors($validator)
                              ->withInput();
          } else {
              // update
              DB::transaction(function() {
                  $user = user::find(Input::get('id'));
                  
                  $user->medical_profession = Input::get('medical_profession');
                  $user->medical_degree = Input::get('medical_degree');
                  $user->office_address = Input::get('office_address');
                  $user->website_url = Input::get('website_url');
                  
                  if (Input::get('new_password') != '') {
                      $user->password = Hash::make(Input::get('new_password'));
                  }

                  if (Input::get('new_email') != '') {
                      $user->email = Input::get('new_email');
                  }

                  $user->save();
              });
              
              $userObj = User::find(Input::get('id'));
              $field_of_interest = $request->get('field_of_interest');
              // TODO: use attach & detach from eloquent
              UserCategory::deleteCategoryData(Input::get('id'));
              if( !empty($field_of_interest) ){
                  foreach($field_of_interest as $category_id){
                      $usercatdata = array();
                      $usercatdata['user_id'] = Input::get('id');
                      $usercatdata['category_id'] = $category_id;
                      $userCatRes = UserCategory::insertUserCategory($usercatdata);
                  }
              }
              User::find(Input::get('id'))->syncMedicalInterestCollections();
              
              return Redirect::to('account')->with('message', 'Profile successfully updated.');
          }
      } // eof submit
      return response()
        ->view('beta.user.account', [ 
          'user' => $data['user'],
          'countBundleCart' => $countBundleCart,
          'categoryData' => Category::ordered()->get(),
          'userCategory'=>$data['category']
        ])
        ->header('Cache-Control', $this->noCacheControlHeader());
  }

  /**
   * GET /user/accountverifyoldpassword
   * Author: Jinandra
   * Date: 26-12-2016
   * Check user's old password
   *
   * param string $old_password
   * @return string
   */
  public function accountVerifyOldPassword(Request $request) {
      
      $strfunction = ($request->get("func") != '')?$request->get("func"):"";
      $old_password = ($request->get("old_password") != '')?base64_decode($request->get("old_password")):"";
      
      if ( $old_password != "" ) {
          if (Hash::check($old_password, Auth::user()->password)) {
              return "OldPasswordRight";
          } else {
              return "OldPasswordWrong";
          }
      } else {
          return "OldPasswordBlank";
      }
  }
    
    
  /**
   * POST /user/changeprofilepassword
   * Author: Jinandra
   * Date: 27-12-2016
   * Change profile password
   *
   * param string $old_password
   * param string $new_password
   * param string $new_password_confirm
   * @return string
   */
  public function changeProfilePassword() {
      
      $rules = [
          'new_password' => 'required|min:6|max:30|same:new_password_confirm',
          'new_password_confirm' => 'required|min:6|max:30',
      ];
      if (Input::get('new_password')) {
     
          $old_password = Input::get('old_password');
          $new_password = Input::get('new_password');
          $new_password_confirm = Input::get('new_password_confirm');
                  
          if (Hash::check($old_password, Auth::user()->password)) {
              
              $validator = Validator::make(Input::all(), $rules);

              if ($validator->fails()) {
                  return Redirect::to('/account')
                                  ->withErrors($validator)
                                  ->withInput();
              } else {
                  // update
                  $user = User::find(Auth::user()->id);
                  $user->password = Hash::make(Input::get('new_password'));
                  $user->save();
                  return Redirect::to('/account')->with('message', 'Password successfully changed.');
              }           
          }           
      }else{
          $validator = Validator::make(Input::all(), $rules);

          if ($validator->fails()) {
              return Redirect::to('/account')
                              ->withErrors($validator)
                              ->withInput();
          }
      }
  }

  private function _isRecaptchaSuccess ($userResponse) {
    $url = config('google.RECAPTCHA_ENDPOINT').'&response='.$userResponse.'&remoteip='.$_SERVER['REMOTE_ADDR'];
    $res = file_get_contents($url);
    return json_decode($res, true);
  }
}
