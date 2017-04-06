<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media,
    App\Models\UcodeHistory,
    App\Models\User,
    App\Models\BundleCart,
    App\Models\History,
    App\Models\Ucode,
    App\Models\Category,
    App\Models\UserRole,
    App\Models\Common;
	

	
use App\Models\Collection;
use App\Models\Favorite;
use App\Models\Learn;
use DB,
    Auth,
    Input,
    Redirect,
    Response,
    View;

use App\Models\Role;

class homeController extends Controller {
    
    /**
     * Display featured, Most Popular 1 Week,
     * Most Popular 1 month of the resource.
     *
     * @return \Illuminate\View\View
     */

  // GET /user
  // logged user landing page (redirected after login)
  public function index() {
    return response()
      ->view('beta.userHome', [
        'medias' => Media::newlyAdded($this->getUserFavorites()),
        'countBundleCart' => BundleCart::getBundleCartCount(Auth::user()->id),
        'categoryData' => Category::ordered()->get()
      ])
      ->header('Cache-Control', $this->noCacheControlHeader());
  }

    /**
     * Save favorite or delete favorite for certain media
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function fav ($id) {

        $media = DB::select('select title from media where id=?', [$id]);
        $favorite = DB::table('favorite')->where('id_media', $id)->get();

        $condition = false;
        if (empty($favorite)) {
            $favorite = new Favorite;
            $favorite->user_id = Auth::user()->id;
            $favorite->id_media = $id;
            $favorite->save();
        } else {
            $condition = true;

            DB::table('favorite')->where('id_media', $id)->delete();
        }

        return ['alert' => $condition, 'title' => array_fetch($media, 'title')];
    }

    public function learn () {
      $medias = DB::table((new Learn)->getTable() . ' AS LRN')
        ->select('M.*')
        ->join(DB::raw('( '.Media::qAll(true)->toSql().' ) AS M'), 'M.id', '=', 'LRN.id_media')
        ->orderBy('LRN.sortOrder', 'ASC')
        ->get();
      return response()
        ->view('beta.learn', [
          'medias' => Media::fillMediaFields($medias),
          'countBundleCart' => BundleCart::getBundleCartCount(Auth::user()->id),
        ])
        ->header('Cache-Control', $this->noCacheControlHeader());
    }

    /**
     * Search Ucode / Media
     *
     * @return \Illuminate\View\View
     */
    public function search() {
      $s = trim(Input::get('s'));
      if ($s != '') {
        preg_match("/\b[a-zA-Z0-9]{5}\b[-]\b[a-zA-Z0-9]{5}\b/", $s, $output_array);
        if (count($output_array) > 0 || strlen($s) === 11) {// Bundle Search
          return Redirect::to('/ucode/' . $s);
        } else {
          // Media Search
          $data = array();
          // TODO: use eloquent instead of direct query
          /* $userQuery = function ($q) use ($s) { */
          /*   $q->where('screen_name', 'LIKE', "%{$s}%") */
          /*     ->orWhere('name', 'LIKE', "%{$s}%") */
          /*     ->orWhere('first_name', 'LIKE', "%{$s}%") */
          /*     ->orWhere('last_name', 'LIKE', "%{$s}%"); */
          /* }; */
          /* $data['folders'] = Collection */
          /*   ::whereHas('user', $userQuery) */
          /*   ->orWhere('name', 'LIKE', "%{$s}%") */
          /*   ->orWhere('description', 'LIKE', "%{$s}%") */
          /*   ->orderBy('created_at', 'DESC') */
          /*   ->get(); */
          /* $media = Media */
          /*   ::whereHas('user', $userQuery) */
          /*   ->orWhere('title', 'LIKE', "%{$s}%") */
          /*   ->orWhere('description', 'LIKE', "%{$s}%") */
          /*   ->orderBy('created_at', 'DESC') */
          /*   ->get(); */

          $data['folders'] = DB::select(
            "SELECT collections.*, users.screen_name AS user_screen_name, users.name AS user_full_name, users.first_name AS user_fname, users.last_name AS user_lname" .
            " FROM collections, users" .
            " WHERE users.id=collections.user_id AND (collections.name LIKE '%$s%' OR collections.description LIKE '%$s%' OR users.screen_name LIKE '%$s%' OR users.name LIKE '%$s%' OR users.first_name LIKE '%$s%' OR users.last_name LIKE '%$s%')" .
            " ORDER BY collections.created_at DESC");
          $media = DB::select(
            "SELECT media.*, users.name AS user_full_name, users.first_name AS user_fname, users.last_name AS user_lname" .
            " FROM media, users" .
            " WHERE media.user_id=users.id AND (media.title LIKE '%$s%' OR media.description LIKE '%$s%' OR users.screen_name LIKE '%$s%' OR users.name LIKE '%$s%' OR users.first_name LIKE '%$s%' OR users.first_name LIKE '%$s%')" .
            " ORDER BY created_at DESC"
          );
          $data['media'] = Media::fillMediaFields($media);

          return response()
            ->view('beta.search', [
              's' => $s,
              'auth' => Auth::check(),
              'data' => $data,
              'countBundleCart' => BundleCart::getBundleCartCount(Auth::user()->id)
            ])
            ->header('Cache-Control', $this->noCacheControlHeader());
        }
      } else {
        return Redirect::to('/');
      }
    }
    
    public function searchByFilters() {
      $query = trim(Input::get('query'));
      $types = explode('-', trim(Input::get('types'), "-"));
      $allSearchTypes = Common::getAllSearchTypes();
      for($i = 0; $i < count($types); $i++) {
        if(!in_array($types[$i], array_keys($allSearchTypes))) {
          unset($types[$i]);
        }
      }
      
      if($query != '') {
        if(preg_match("/\b[a-zA-Z0-9]{5}\b[-]\b[a-zA-Z0-9]{5}\b/", $query)) {
          $ucode = Ucode::where('ucode', $query)->first();
          if($ucode) {
            return Redirect::to('/ucode/'.$query);
          }
        }
        return response()
          ->view('beta.searchFilter', [
            'query' => $query,
            'countBundleCart' => BundleCart::getBundleCartCount(Auth::user()->id),
            'allSortTerms' => Common::getAllSortTerms(),
            'allSearchDates' => Common::getAllSearchDates(),
            'allSearchTypes' => $allSearchTypes,
            'checkedSearchTypes' => $types,
          ])
          ->header('Cache-Control', $this->noCacheControlHeader());
      }
      return Redirect::to('/');
    }
    
    public function searchByFiltersViaAjax() {
      $query = trim(Input::get('query'));
      $sort = Input::get('sort');
      $date = Input::get('date');
      $types = explode('-', trim(Input::get('types'), "-"));
      
      $mediaSearchTypes = [];
      $allSearchTypes = Common::getAllSearchTypes();
      for($i = 0; $i < count($types); $i++) {
        if(in_array($types[$i], Common::getAllMediaSearchTypes())) {
          $mediaSearchTypes[] = $types[$i];
        }
        if(!in_array($types[$i], array_keys($allSearchTypes))) {
          unset($types[$i]);
        }
      }
      
      $allSortTerms = Common::getAllSortTerms();
      if(!in_array($sort, array_keys($allSortTerms))) {
        $sort = Common::SEARCH_SORT_MOST_POPULAR;
      }
      $allSearchDates = Common::getAllSearchDates();
      if(!in_array($date, array_keys($allSearchDates))) {
        $date = Common::SEARCH_DATE_TODAY;
      }
      $user = in_array(Common::SEARCH_TYPE_USER, $types);
      if(empty($types) || in_array(Common::SEARCH_TYPE_ALL, $types)) {
        $types = [Common::SEARCH_TYPE_ALL];
      }
      if($user) {
          $types[] = Common::SEARCH_TYPE_USER;
      }
      
      if($query != '') {
        $data = [];
        if(in_array(Common::SEARCH_TYPE_ALL, $types) || in_array(Common::SEARCH_TYPE_FOLDER, $types)) {
          $data['folders'] = Common::searchCollections($query, $sort, $date, $user);
        }
        
        if(in_array(Common::SEARCH_TYPE_ALL, $types) || !empty($mediaSearchTypes)) {
          $data['media'] = Common::searchMedia($query, $sort, $date, $mediaSearchTypes, $user);
        }
        
        return Response::json([
          'success' => true,
          'data' => (String) view('beta.searchFilterAjax', ['data' => $data])
        ]);
      }
      return Response::json([
        'success' => false
      ]);
    }

    private function _addUcodeHistory ($ucodeId, $userId) {
      $history = new UcodeHistory;
      $history->ucode_id = $ucodeId;
      $history->user_id  = $userId;
      $history->save();
    }

    private function _addMediaHistory ($mediaId, $userId) {
      $history = new History;
      $history->id_media = $mediaId;
      $history->user_id  = $userId;
      $history->save();
    }

    /**
     * View Ucode Single Page
     * 	@param String $ucode
     *
     * @return \Illuminate\View\View
     */
    public function singleUcode ($ucode) {
      $object = Ucode::findByUCode($ucode);

      if ( is_null($object) ) {
        return Redirect::to('/?ucodeNotFound=UCode ' . $ucode . ' not found');
      }

      $media = $object->statsMedia();
      if (Auth::check()) {
        if ($object->user_id != Auth::user()->id) { // add history when not creator
          $this->_addUcodeHistory($object->id, Auth::user()->id);
          if (count($media) > 0) {
            $this->_addMediaHistory($media[0]->id, Auth::user()->id);
          }
        }
      } else {
        if ( strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'disqus') === FALSE ) { // ignore disqus callback
          $this->_addUcodeHistory($object->id, null);
          if (count($media) > 0) {
            $this->_addMediaHistory($media[0]->id, null);
          }
        }
      }
      if (Auth::check()) {
        return response()
          ->view('beta.ucode_single', [
            'media' => $media, 
            'ucode' => $ucode,
            'countBundleCart' => BundleCart::getBundleCartCount(Auth::user()->id),
            'collections' => $this->getCollections()
          ])
          ->header('Cache-Control', $this->noCacheControlHeader());
      } else {
          return view('beta.ucode_single_guest', ['media' => $media, 'ucode' => $ucode]);
      }
    }
}

?>
