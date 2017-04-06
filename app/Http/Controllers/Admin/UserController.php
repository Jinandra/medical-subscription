<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User,
    App\Models\Role,
    App\Models\Ucode,
    App\Models\Media,
    App\Models\Collection;
use Carbon\Carbon;
use Input,
    Validator,
    Redirect,
    Hash,
    DB,
    Auth,
    Mail;

class UserController extends Controller {

  /**
   * GET /admin/user/pendings
   * Display user lists which are pending verification
   * TODO: move to admin controller
   */
  public function pendingVerification () {
    $data['page_title'] = "View Pending Users";


    $data['users'] = User::where(function($query) {
      $query->where(function ($searchable) {
        $keyword = Input::get('s');
        $searchable->where('first_name', 'like', "%{$keyword}%")
          ->orWhere('last_name', 'like', "%{$keyword}%")
          ->orWhere('email', 'like', "%{$keyword}%")
          ->orWhere('screen_name', 'like', "%{$keyword}%");
      })
      ->where('user_status', 'need_verification');
    })
    ->orderBy('created_at', 'desc')
    ->paginate(10);

    return view('admin.user.pendings', $data);
  }

  /**
   * POST /admin/user/{id}/verify
   * activate/declinet user pending status (verification)
   * TODO: move to admin controller
   */
  public function verify ($id) {
    $action = Input::get('action');
    $user   = User::find($id);
    $message = "User {$user->fullnameWithScreenName()}";
    switch ($action) {
    case 'activate':
      $user->activated();
      $message .= " was activated";
      Mail::send('emails.accountActivated', ['user' => $user], function ($m) use ($user) {
        $m->from(config('app.EMAIL_ADDRESS_FROM'), config('app.EMAIL_SUBJECT_FROM'));
        $m->to($user->email, $user->fullname())->subject("Your request has been approved by Enfolink.com");
      });
      break;
    case 'decline':
      $user->declined(Input::get('decline_message'));
      $message .= " was declined";
      Mail::send('emails.accountDeclined', ['user' => $user, 'reviewer_email' => config('app.REVIEWER_EMAIL') ], function ($m) use ($user) {
        $m->from(config('app.EMAIL_ADDRESS_FROM'), config('app.EMAIL_SUBJECT_FROM'));
        $m->to($user->email, $user->fullname())->subject("Your request has been declined by Enfolink.com");
      });
      break;
    }
    return Redirect::to("admin/user/$id")
      ->with('message', $message);
  }

  /**
   * GET /admin
   * Show admin dashboard
   * TODO: move to admin controller
   */
  public function dashboard () {
    $query = <<<QUERY
SELECT users.*
FROM users
INNER JOIN role_user ON users.id=role_user.user_id
WHERE role_user.role_id = ? AND users.id NOT IN (
  SELECT  users.id AS id FROM `users`
  INNER JOIN role_user ON users.id=role_user.user_id
  WHERE role_user.role_id != ?
)
QUERY;
    $regularUserRoleId = Role::regularUser()->first()->id;
    $records = DB::select(DB::raw($query), [$regularUserRoleId, $regularUserRoleId]);

    $data = [
      'page_title' => 'Admin Dashboard',
      'user' => [
        'total_all' =>  User::count(),
        'total_unverified' =>  User::where('user_status', 'need_verification')->count(),
        'total_regular_user' => count($records),
        'total_paid_user' =>  Role::paidUser()->first()->users()->count(),
        'total_admin' =>  Role::administrator()->first()->users()->count()
      ],
      'media' => [
        'total_all' =>  Media::count(),
        'total_video' =>  Media::where('type', Media::TYPE_VIDEO)->count(),
        'total_image' => Media::where('type', Media::TYPE_IMAGE)->count(),
        'total_document' =>  Media::where('type', Media::TYPE_DOCUMENT)->count(),
        'total_website' =>  Media::where('type', Media::TYPE_WEBSITE)->count()
      ],
      'ucode' => [
        'total_all' => Ucode::count()
      ],
      'collection' => [
        'total_all' => Collection::count()
      ]
    ];
    return view('admin.dashboard.index', $data);
  }

  /**
   * GET /admin/user
   * View all users
   * TODO: move to admin
   */
  public function index () {
    $data['page_title'] = "View Users";
    $data['role_administrator'] = Role::administrator()->first();
    $data['role_paid_user'] = Role::paidUser()->first();

    $data['users'] =
      User::where(function($query) {
        $query->where('first_name', 'like', '%' . Input::get('s') . '%');
        $query->orWhere('last_name', 'like', '%' . Input::get('s') . '%');
        $query->orWhere('email', 'like', '%' . Input::get('s') . '%');
        $query->orWhere('screen_name', 'like', '%' . Input::get('s') . '%');
      })
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    return view('admin.user.view_user', $data);
  }

  /**
   * DELETE /admin/user/{id}
   * admin::user::delete
   * Delete the user
   * TODO: admin
   */
  public function delete (Request $request, $id) {
    if ( !is_null($id) ) {
      $user = user::find($id);
      $user->delete();

      return redirect()->route('admin::user::index')->with('message', "User {$user->screen_name} Deleted");
    } else {
      $data['page_title'] = "Something Wrong";
      $data['message'] = "param id is needed";
      return view('admin.error', $data);
    }
    return view('admin.user.view_user', $data);
  }

  /**
   * GET /admin/user/new
   * admin::user::new
   * Display form to add new user
   * TODO: admin
   */
  public function addNew () {
    $data['page_title'] = "Add new user";
    return view('admin.user.new', $data);
  }

  /**
   * POST /admin/user
   * admin::user::create
   * Create new user from admin UI
   */
  public function create (Request $request) {
    $rules = [
      'screen_name' => 'required|min:3|max:100|alpha_dash|unique:users,screen_name',
      'password' => 'required|min:6|max:30|same:password_confirmation',
      'password_confirmation' => 'required|min:6|max:30',
      'email' => 'email|max:255|unique:users,email',
    ];

    $validator = Validator::make(Input::all(), $rules);
    if ($validator->fails()) {
      return redirect()->route('admin::user::new')->withErrors($validator)->withInput();
    } else {
      $user = DB::transaction(function () use ($request) {
        $user = new User;
        $user->screen_name = $request->get('screen_name');
        $user->email = $request->get('email');
        $user->name        = "{$request->get('first_name')} {$request->get('last_name')}";
        $user->first_name  = $request->get('first_name');
        $user->last_name   = $request->get('last_name');
        $user->password    = Hash::make($request->get('password'));
        $user->user_status = 'active';
        $user->created_by  = Auth::user()->id;
        $user->save();
        $user->attachRole(Role::regularUser()->first());
        switch ($request->get('role')) {
        case Role::MASTER_ADMINISTRATOR:
          $user->attachRole(Role::masterAdministrator()->first());
          $user->attachRole(Role::administrator()->first());
          $user->attachRole(Role::paidUser()->first());
          break;
        case Role::ADMINISTRATOR:
          $user->attachRole(Role::administrator()->first());
          $user->attachRole(Role::paidUser()->first());
          break;
        case Role::PAID_USER:
          $user->attachRole(Role::paidUser()->first());
          break;
        }
        return $user;
      });

      return redirect()->route('admin::user::index');
    }
  }

  /**
   * GET|POST /admin/user/change-password
   * Change user password by admin
   */
  public function changePassword () {
      $data['page_title'] = "Change Password";
      $data['user'] = User::find(Input::get('id'));

      if (Input::get('submit')) {
          $rules = array(
              'password' => 'required|min:6|same:password_confirmation',
              'password_confirmation' => 'required|min:6',
          );
          $validator = Validator::make(Input::all(), $rules);

          if ($validator->fails()) {
              return Redirect::to('admin/user/change-password?id=' . Input::get('id'))
                              ->withErrors($validator);
          } else {
              $user = User::find(Input::get('id'));
              $user->password = Hash::make(Input::get('password'));
              $user->save();

              return Redirect::to('admin/user/change-password?id=' . Input::get('id'))->with('message', 'Password successfully changed.');
          }
      }

      return view('admin.user.change_password', $data);
  }

  /**
   * PATCH /admin/user/{id}
   * admin::user::patch
   * action: 'toggle-role', 'toggle-block'
   */
  public function patch (Request $request, $id) {
    switch ($request->input('action')) {
      case 'toggle-role':
        return $this->_toggleRole($id, $request);
        break;
      case 'toggle-block':
        return $this->_toggleBlock($id, $request);
        break;
      default:
        if ($request->ajax()) {
          return response()->json([ 'error' => 'invalid action' ]);
        }
        return back()->with('message', 'Invalid action.');
    }
  }

  /**
   * GET /admin/user/view
   * Show user in popup
   * @param int $id user id
   */
  public function viewUser() {
      $data['page_title'] = "View User";
      $data['user'] = User::find(Input::get('id'));

      return view('admin.user.view_single_user', $data);
  }

  /**
   * GET /admin/user/{id}
   * Show user
   * @param int $id user id
   */
  public function show ($id) {
    $data['page_title'] = "View User";
    $data['user'] = User::find($id);

    return view('admin.user.show', $data);
  }

  /**
   * GET /admin/user/ucodes
   * admin::user::ucodes
   * Get list of all users with ucodes count
   * @param $request.s ucode query
   */
  public function ucodes (Request $request) {
    $search = $request->query('s');
    
    // Get all user's ucode list
    $users =
      DB::table((new User)->getTable().' AS USR')
        ->select('USR.*', DB::raw('COUNT(UCO.id) AS ucodeCount'))
        ->leftJoin((new Ucode)->getTable().' AS UCO', 'UCO.user_id', '=', 'USR.id')
        ->where(function ($query) use($search){
          if( !empty($search) ) {
            $query->where('USR.screen_name', 'LIKE', '%'.$search.'%')
              ->orWhere('USR.first_name', 'LIKE', '%'.$search.'%')
              ->orWhere('USR.last_name', 'LIKE', '%'.$search.'%');
          }
        }) 
        ->groupBy('USR.id')
        ->orderBy('USR.first_name', 'ASC')
        ->orderBy('USR.last_name', 'ASC')
        ->paginate(10);
    $data = [
      'page_title' => 'Ucode User',
      's' => $search,
      'users' => $users
    ];
    return view('admin.user.user_list_forucode', $data);
  }

  /**
   * GET /admin/user/{id}/ucodes
   * admin::user::userUcodes
   * Show user's ucodes
   * @param int $id user id
   */
  public function detailUcodes (Request $request, $id) {
    $user = User::find($id);
    $search = Ucode::normalize($request->query('s'));
    $result = $user->statsUcodes($search);
    $medias = "";
    if ($result->count() > 0) {
      $medias = Ucode::find($result[0]->id)->statsMedia();

      for ($i = 0; $i < count($result); $i++) {
        $result[$i]->created_at = Carbon::parse($result[$i]->created_at);
        $result[$i]->created_at = $result[$i]->created_at->format('m/d/Y');

        $result[$i]->uCodeHistoryCreatedAt = Carbon::parse($result[$i]->uCodeHistoryCreatedAt);
        $result[$i]->uCodeHistoryCreatedAt = $result[$i]->uCodeHistoryCreatedAt->format('m/d/Y');
        $result[$i]->updated_at = Carbon::parse($result[$i]->updated_at);
        $result[$i]->updated_at = $result[$i]->updated_at->format('m/d/Y');
        $result[$i]->isInBundle = $result[$i]->countOnBundle > 0 && $result[$i]->countOnUcode === $result[$i]->countOnBundle;
      }
      $ucode = $result[0]->ucode;
    } else {
      $ucode = "";
    }

    $data = [
      'page_title' => "User {$user->fullnameWithScreenName()} ucodes",
      's' => $request->query('s'),
      'user' => $user,
      'ucode' => $ucode,
      'ucodes' => $result,
      'medias' => $medias,
      'paginator' => $result
    ];
    return view('admin.user.user_ucode_list', $data);
  }

  /**
   * GET /admin/user/{ucode}
   * param string $ucode
   * @return array
   */
  public function viewUCodeMedia(Request $request, $ucode) {
    $result = Ucode::findByUcode($ucode)->statsMedia();
    return view('admin.media.ucode_media_list', [
      'media' => $result,
      'ucode' => $ucode
    ]);
  }


  // Toggle user role
  private function _toggleRole ($id, $request) {
    $user = User::find($id);
    $role = Role::find($request->input('role'));
    if ($user->hasRole($role->name)) {
      $user->detachRole($role);
    } else {
      $user->attachRole($role);
    }
    if ($request->ajax()) {
      return response()->json($user->roles());
    }
    return back()->with('message', "User '{$user->screen_name}' for role '{$role->display_name}' updated");
  }

  // Toggle user block
  private function _toggleBlock ($id, $request) {
    $user = User::find($id);
    $msg  = '';
    if ($user->isStatusActive()) {
      $user->blocks();
      $msg = 'blocked';
    } else {
      $user->unblocks();
      $msg = 'unblocked';
    }
    if ($request->ajax()) {
      return response()->json(['user_status' => $user->user_status]);
    }
    return back()->with('message', "User '{$user->screen_name}' {$msg}");
  }
}
