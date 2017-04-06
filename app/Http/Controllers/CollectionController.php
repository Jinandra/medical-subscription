<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Media;
use App\Models\History;
use App\Models\LikeDislike;
use App\Models\Favorite;
use App\Models\Collection;
use App\Models\CollectionDetail;
use App\Models\CollectionsHistory;
use App\Models\PseudoCollection;
use App\Models\BundleCart;

use Input,
    Validator,
    Redirect,
    Hash,
    DB,
    Auth,
    Mail,
    Session,
    Form;

class CollectionController extends Controller {

  public function __construct() {
      $this->middleware('auth', ['except' => 'item']);
  }

  /**
   * GET /collection
   * Listing current users's collections
   */
  public function index (Request $request) {
    return response()
      ->view('beta.collection.index', $this->_indexData($request))
      ->header('Cache-Control', $this->noCacheControlHeader());
  }

  /**
   * GET /collection/{id}/preview
   * Display quick view template of a collection
   * @param int $id collection id
   */
  public function preview (Request $request, $id) {
    $request->session()->put('selectedCollectionPreviewId', $id);
    if ($request->ajax()) {
      return view('beta.collection.content', [
        'collection' => Collection::findExtended($id)
      ]);
    }
    return view('beta.collection.index', $this->_indexData($request));
  }

  /**
   * GET /collection/{id}/content
   * Display collection content (the collection and its media)
   * @param int $id collection id
   */
  public function content (Request $request, $id) {
    if ($request->ajax()) {
      return view('beta.collection.content', [
        'collection' => Collection::findExtended($id),
        'centerSelect' => true
      ]);
    }
    return back();
  }

  /**
   * PATCH /collection
   * Modify the collections
   * @param string action (sort | move | copy)
   */
  public function patchCollections (Request $request) {
    switch ($request->input('action')) {
      case 'sort':
        return $this->_sort($request);
        break;
      case 'move':
        return $this->_move($request);
        break;
      case 'copy':
        return $this->_copy($request);
        break;
      default:
        if ($request->ajax()) {
          return response()->json([ 'error' => 'invalid action' ]);
        }
        return back();
    }
  }

  /**
   * DELETE /collections
   * Delete collections or collection's media
   */
  public function deleteCollections (Request $request) {
    $collections = $request->input('collections');
    foreach ($collections as $collectionId => $media) {
      if (Collection::isPseudo($collectionId)) {
        switch ($collectionId) {
          case PseudoCollection::ID_BOOKMARKED:
            PseudoCollection::deleteBookmarked($media);
            break;
          case PseudoCollection::ID_LIKED:
            PseudoCollection::deleteLiked($media);
            break;
          default:
            // history, basic, contributed should not be deleted
            break;
        }
      } else {
        // copied folder can delete whole folder, but not individual media
        $collection = Collection::find($collectionId);
        if ( in_array('self', $media) && !$collection->isCategory() ) {
          Collection::deleteExtended($collectionId);
        } else {
          if ( $collection->isOriginal() ) {
            Collection::deleteMedia($collectionId, $media);
          }
        }
      }
    }
    return back();
  }

  /**
   * POST /collection
   * Create new collection
   */
  public function store (Request $request) {
    $validator = $this->_validateRequest(Input::get('id'));

    if ($validator->fails()) {
      if ($request->ajax()) {
        $errorMessages = array();
        foreach ($validator->errors()->all() as $error) {
          array_push($errorMessages, $error);
        }
        return response()->json([
          'error' => true,
          'message' => $errorMessages
        ]);
      }
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    // save collection
    $collection_id = Input::get('id');
    $is_update     = !is_null($collection_id);
    $collection = new Collection;
    if ( !is_null($collection_id) ) { // update
      $collection = Collection::find($collection_id);
    }
    $collection->user_id      = Auth::user()->id;
    $collection->name         = Input::get('name');
    $collection->description  = Input::get('description');
    $collection->save();

    // Sync children
    foreach ($collection->children as $child) {
      $child->name = $collection->name;
      $child->description = $collection->description;
      $child->save();
    }

    $successMessage  = "Folder ".Input::get('name');
    $successMessage .= ($is_update ? ' updated ' : ' created ').'successfully';
    if ($request->ajax()) {
      return response()->json($collection->toArray());
    }
    return back()
      ->with('message', $successMessage);
  }

  /**
   * PATCH /collection/{id}
   * Modify a single collection object
   */
  public function patchSingle (Request $request) {
    return $this->store($request);
  }

  /**
   * GET /collection/pin?(pin=id | unpin=id}
   * Pin or unpin a collection
   */
  public function pinCollection (Request $request) {
    $collection = null;
    $pinId   = $request->query('pin');
    $unpinId = $request->query('unpin');
    if( !is_null($pinId) && $pinId !== '' ) {
      $collection = Collection::pin($pinId, true);
    } else if( !is_null($unpinId) && $unpinId !== '') {
      $collection = Collection::pin($unpinId, false);
    }
    if ($request->ajax()) {
      return response()->json($collection);
    }
    return back();
  }

  /**
   * GET /folder/{id}
   * Show collection & media for public view
   */
  public function folderDetail ($id) {
    $user_id = Auth::user()->id;
    $result_arr = DB::select(
      "SELECT *, c.id AS preview_id" .
      " FROM collection_details c, media m" .
      " WHERE c.media_id=m.id AND c.collection_id='".$id."' AND m.private='".Media::STATUS_PUBLIC."'" .
      " ORDER BY c.sort_order ASC, c.id DESC"
    );
    $media = Media::fillMediaFields($result_arr);
    $folder_arr = DB::table('collections')->select('*')->where('id', $id)->first();
    if (is_null($folder_arr)) {
      abort(404);
    }

    // ADD TO HISTORY
    $col_history = new CollectionsHistory();
    $col_history->collection_id = $id;
    if (Auth::check()) {
      if ($folder_arr->user_id != $user_id) {
        $col_history->user_id = Auth::user()->id;
        $col_history->save();
      }
    } else {
      $col_history->user_id = null;
      $col_history->save();
    }

    //dd($media);	
    return response()
      ->view('beta.collection.folder_detail', [
        'media' => $media,
        'folder_name' => $folder_arr->name,
        'countBundleCart' => BundleCart::getBundleCartCount(Auth::user()->id),
        'collections' => $this->getCollections()
      ])
      ->header('Cache-Control', $this->noCacheControlHeader());
  }

  /**
   * GET /exists?user_id=user_id&name=collection_name&[id=collection_id]
   * Check folder existance for current user
   */
  public function isExist (Request $request) {
    $q = Collection
      ::where('user_id', Auth::user()->id)
      ->whereNull('category_id')
      ->whereNull('original_id')
      ->where('name', 'like', $request->input('name'));
    if (!is_null($request->input('id'))) {
      $q->where('id', '!=', $request->input('id'));
    }
    if ($q->count() > 0) {
      abort(400, "Folder {$request->input('name')} already exists");
    }
    return "1";
  }

  /**
   * POST /collection/bundle
   * Bulk bundle of collections or collection's media
   */
  public function bulkBundle (Request $request) {
    $collections = $request->input('collections');
    if (!is_null($collections)) {
      foreach ($collections as $collectionId => $media) {
        if (Collection::isPseudo($collectionId)) {
          BundleCart::bulkAddMedia($media);
          break;
        } else {
          if (in_array('self', $media)) {
            Collection::addToBundle($collectionId);
          } else {
            if( isset($media) && !empty($media) )  {
              $mediaIdsArr = [];  // don't add medium that already in bundle cart
              foreach( $media as $mediumId ) {
                $mediaData = CollectionDetail::findMediaOrderByCollectionId($collectionId, $mediumId);
                $mediaSortNumber = $mediaData->sort_order;
                $mediaIdsArr[$mediaSortNumber] = $mediumId;
              }
              BundleCart::bulkAddMedia($mediaIdsArr);
            }
          }
        }
      }
    }
    if ($request->ajax()) {
      return response()->json([ 'countBundleCart' => BundleCart::getBundleCartCount(Auth::user()->id)[0] ]);
    }
    return back();
  }

  /**
   * PUT /collection/gridview
   * Save grid layout view
   */
  public function saveGridLayout (Request $request) {
    $gridView = Input::get('layout');
    Session::set('gridView', $gridView);
    if ($request->ajax()) {
      return response()->json(['gridView' => $gridView]);
    }
    return back();
  }

  /**
   * GET /bundle?id=collection_id&action={remove || add}
   * Add / remove a single collection to bundle cart
   */
  public function bundle (Request $request, $id, $action) {
    switch ($action) {
    case 'remove':
      Collection::removeFromBundle($id);
      break;
    case 'add':
      Collection::addToBundle($id);
      break;
    }
    if ($request->ajax()) {
      return response()->json([ 'countBundleCart' => BundleCart::getBundleCartCount(Auth::user()->id)[0] ]);
    }
    return back();
  }

  /**
   * POST /folder/{$id}
   * Copy a folder (used by search)
   */
  public function copyFolder(Request $request, $id) {
    $authUserId = Auth::user()->id;
    $originalId = $id;
    $original = Collection::whereId($originalId)->where('user_id', '!=', $authUserId)->first();
    if($original && $original->original_id) {
      $original = Collection::whereId($original->original_id)->where('user_id', '!=', $authUserId)->first();
    }
    $message = '';
    if($original) {
      $originalId = $original->id;
      $copiedCollections = Collection::where('user_id', '=', $authUserId)
                                    ->where('original_id', '=', $originalId)
                                    ->get();
      if($copiedCollections->count() == 0) {
        $collection = new Collection;
        $collection->user_id = $authUserId;
        $collection->name = $original->name;
        $collection->description = $original->description;
        $collection->original_id = $originalId;
        $collection->save();

        $collectionDetails = CollectionDetail::where('collection_id', $originalId)->get();
        if($collectionDetails->count() > 0) {
          foreach($collectionDetails as $collectionDetail) {
            $cd = new CollectionDetail;
            $cd->user_id = $authUserId;
            $cd->collection_id = $collection->id;
            $cd->sort_order = $collectionDetail->sort_order;
            $cd->media_id = $collectionDetail->media_id;
            $cd->save();
          }
        }
        $message = 'copied';
        $data = (String)view('beta.partials.search.folder', ['folder' => $collection]);
      } else {
        $data = [];
        foreach($copiedCollections as $copiedCollection) {
          if(DB::table('collections')->where('id', '=', $copiedCollection->id)->delete()) {
            $data[] = $copiedCollection->id;
          }
        }
        $message = 'removed';
      }
    }
    if ($request->ajax()) {
      return response()->json([
                            'message' => $message,
                            'data' => $data,
                            'originalId' => $originalId
                        ]);
    }
    return back();
  }

  // sort the media collection
  private function _sort (Request $request) {
    $collection_id = $request->input('collection_id');
    $media = $request->input('media');
    Collection::resort($collection_id, $media);
    if ($request->ajax()) {
      return response()->json(['collection_id' => $collection_id, 'media' => $media]);
    }
    return back();
  }

  // move the folder's media to another folder
  private function _move (Request $request) {
    $collections  = $request->input('collections');
    $targets      = $request->input('targets');
    if (!is_null($targets)) {
      foreach ($collections as $srcCollectionId => $media) {
        if (in_array('self', $media)) {
          Collection::moveCollection($srcCollectionId, $targets);
        } else {
          if (Collection::isPseudo($srcCollectionId)) {
            Collection::copyMedia($media, $targets);
          } else {
            Collection::moveMedia($srcCollectionId, $media, $targets);
          }
        }
      }
    }
    if ($request->ajax()) {
      return response()->json(['collections' => $collections, 'targets' => $targets]);
    }
    return back();
  }

  // copy the folder's media to another folder
  private function _copy (Request $request) {
    $collections  = $request->input('collections');
    $targets      = $request->input('targets');
    $media        = $request->input('media');
    if (!is_null($targets)) {
      if (!is_null($media)) {
        Collection::copyMedia($media, $targets);
      }
      if (!is_null($collections)) {
        foreach ($collections as $srcCollectionId => $media) {
          if (in_array('self', $media)) {
            Collection::copyCollection($srcCollectionId, $targets);
          } else {
            Collection::copyMedia($media, $targets);
          }
        }
      }
    }
    if ($request->ajax()) {
      return response()->json(['collections' => $collections, 'media' => $media, 'targets' => $targets]);
    }
    $redirectUrl = $request->input('redirectURL');
    if (!is_null($redirectUrl)) {
      return redirect($redirectUrl);
    }
    return back();
  }

  // validate request for create & edit
  private function _validateRequest ($editId = null) {
    Validator::extend('uniquePerUser', function ($field, $value, $parameters) {
      $editColId  = $parameters[0];
      $collection = Collection
        ::where('name', $value)
        ->whereNull('original_id')
        ->whereNull('category_id')
        ->where('user_id', Auth::user()->id)
        ->first();
      if ( is_null($collection) ) { return true; }
      if ( $collection->id == $editColId && $collection->user_id === Auth::user()->id) { // edited by owner
        return true;
      }
      return false;
    });
    return Validator::make(Input::all(), [
        'name' => 'required|min:3|max:150|uniquePerUser:'.$editId,
        'description' => 'max:1000',
      ],
      ['name.unique_per_user' => 'You already have a folder with this name.']
    );
  }

  // get data for collection listing
  private function _indexData (Request $request) {
    $sortByPinned = function ($a, $b) {
      if ($a->is_pin > $b->is_pin) { return -1; }
      if ($a->is_pin < $b->is_pin) { return 1; }
      return strcmp($a->name, $b->name);
    };
    $created    = Collection::listMine()->sort($sortByPinned);
    $saved      = Collection::listSaved()->sort($sortByPinned);
    $categoried = Collection::listCategoried()->sort($sortByPinned);
    $data = array(
      'all'     => $created->merge($saved)->sort($sortByPinned),
      'created' => $created,
      'saved'   => $saved,
      'categoried' => $categoried,
      'pseudos' => PseudoCollection::listAll()->sort($sortByPinned),
      'pinneds' => Collection::listPinned(),
      'countBundleCart' => BundleCart::getBundleCartCount(Auth::user()->id)
    );
    $selectedCollectionPreviewId = $request->session()->get('selectedCollectionPreviewId');
    if ( !is_null($selectedCollectionPreviewId) ) {
      $data['currentPreview'] = Collection::findExtended($selectedCollectionPreviewId);
    }
    return $data;
  }

}
