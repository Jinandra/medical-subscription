<?php

namespace App\Http\Controllers;

use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB,
    Auth,
    Input,
    Mail,
    Response,
    App,
    Redirect,
    Storage;
use App\Models\Media;
use App\Models\MediaReport;
use App\Models\User;
use App\Models\History;
use App\Models\LikeDislike;
use App\Models\Collection;
use App\Models\CollectionDetail;
use App\Models\Category;
use App\Models\BundleCart;
use App\Models\State;
use App\Util;
use Validator;
use Log;




class MediaController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
    }

    /**
     * GET /contribute
     * List all media belongs to current user
     */
    public function index (Request $request, $type = null) {
      if(!$type || !array_key_exists($type, Media::getAllShowTypes())) {
        $type = '';
      }
      $query = Media::qAll()->where('M.user_id', Auth::user()->id);
      switch ($type) {
        case Media::SHOW_TYPE_ONLINE:
          $query->whereNotNull('M.web_link')
                ->where('M.web_link', '!=', '');
          break;
        case Media::SHOW_TYPE_UPLOADED:
          $query->whereNotNull('M.file_name')
                ->where('M.file_name', '!=', '');
          break;
      }
      $sortBy = strtolower($request->query('sort') === null ? 'DESC' : $request->query('sort'));
      if ($sortBy !== 'asc' || $sortBy !== 'desc') {
        $sortBy = 'DESC';
      }
      $query->orderBy('M.created_at', $sortBy);

      $media = Media::fillMediaFields($query->get());

      return response()
        ->view('beta.media.index', [
          'media' => $media,
          'type' => $type,
          'countBundleCart' => BundleCart::getBundleCartCount(Auth::user()->id),
          'collections' => $this->getCollections()
        ])
        ->header('Cache-Control', $this->noCacheControlHeader());
    }

    /**
     * GET /contribute/addForm
     * Show form to add media
     */
    public function addForm() {
      return response()
        ->view('beta.media.add', [
          'countBundleCart' => BundleCart::getBundleCartCount(Auth::user()->id),
          'categories' => Category::ordered()->get(),
          'states' => State::active()->nameOrdered()->get(),
          'collections' => $this->getCollections(),
          'media' => $this->toInputArray()
        ])
        ->header('Cache-Control', $this->noCacheControlHeader());
    }

    // Parse form request
    private function parseRequest ($request, $id = null) {
      if ( !is_null($id) ) {
        $media = Media::find($id);
        $media->old_file = $media->file_name;
      } else {
        $media = new Media;
      }
      if($request->create_type == Media::CREATE_TYPE_UPLOAD) {
        $file = $request->file('media');
        if($file && !$media->id) {
          $media->file_name = $file->getClientOriginalName();
        }
        $media->web_link = '';
      } elseif($request->create_type == Media::CREATE_TYPE_ONLINE) {
        if(!$media->id) {
          $media->web_link = $request->web_link;
        }
        $media->file_name = '';
      }
      $media->title = $request->title;
      $media->description = $request->description;
      $media->source = $request->source;
      $media->user_expertise = $request->user_expertise;
      $media->target_audience = serialize($request->target_audience);
      $media->state_id = $request->state_id?$request->state_id:null;
      $media->city = $request->city?$request->city:null;
      $media->area = $request->area?$request->area:null;
      $media->caption_available = $request->caption_available?$request->caption_available:0;
      if(!$media->id) {
        $media->type = strtolower($request->type);
      }
      $customTags = explode(',', $request->tags);
      foreach ($customTags as $cTag) {
        $tags[] = $cTag;
      }
      $media->tag = json_encode($tags);
      $media->private = $request->private;
      return $media;
    }

    // add unique media error if any
    private function addUniqueMediaError ($validator, Request $request) {
      // add media unique error
      if ( in_array('validation.unique_media', $validator->errors()->get('web_link')) ) {
        // TODO: remove validation.unique_media key
        $medium = Media::where('web_link', $request->input('web_link'))->first();
        if ($medium->user_id === Auth::user()->id) {
          $validator->errors()->add('web_link', "You already submit this media under title: {$medium->title}");
        } else {
          Session::reflash();
          $validator->errors()->add('web_link', "Media already submitted by: {$medium->user->screen_name}");
          Session::flash('duplicate_media', Media::findSingle($medium->id));
        }
      }
      return $validator;
    }

    private function validateRequest ($request, $editId = null) {
      $createTypesString = '';
      foreach(array_keys(Media::getAllCreateTypes()) as $type) {
        $createTypesString .= $type.',';
      }
      $createTypesString = substr($createTypesString, 0, -1);
      
      if($request->create_type == Media::CREATE_TYPE_UPLOAD) {
        $types = array_keys(Media::getAllUploadTypes());
      } else {
        $types = array_keys(Media::getAllOnlineTypes());
      }
      $typesString = '';
      foreach($types as $type) {
        $typesString .= $type.',';
      }
      $typesString = substr($typesString, 0, -1);
      
      Validator::extend('allowedExt', function ($field, $value, $parameters) use($request) {
        $file = $request->file('media');
        $ext = $file->guessClientExtension();
        if(in_array($ext, Media::getAllowedExtensions())) {
          return true;
        }
        return false;
      });
      Validator::extend('maxSize', function ($field, $value, $parameters) use($request) {
        $file = $request->file('media');
        $size = $file->getClientSize();
        if($size < Auth::user()->getMediaAllowedSize()) {
          return true;
        }
        return false;
      });
      Validator::extend('uniqueMedia', function ($field, $value, $parameters) use($request) {
        if($request->file('media')) {
          return true;
        }
        $editId = $parameters[0];
        $medium = Media::where('web_link', $value)->first();
        if ( is_null($medium) ) { return true; }
        if ( $medium->id == $editId && $medium->user_id === Auth::user()->id) { // edited by owner
          return true;
        }
        return false;
      });
      
      $sourcesString = '';
      foreach(array_keys(Media::getAllSources()) as $source) {
        $sourcesString .= $source.',';
      }
      $sourcesString = substr($sourcesString, 0, -1);
      
      $expertisesString = '';
      foreach(array_keys(Media::getAllExpertiseLevels()) as $level) {
        $expertisesString .= $level.',';
      }
      $expertisesString = substr($expertisesString, 0, -1);
      
      Validator::extend('validAudiences', function ($field, $value, $parameters) {
        $validAudiences = array_intersect($value, array_keys(Media::getAllAudiences()));
        if(count($validAudiences) == count($value)) {
          return true;
        }
        return false;
      });
      Validator::extend('validState', function ($field, $value, $parameters) {
        $state = State::find($value);
        if(!$value || $state) {
          return true;
        }
        return false;
      });
      Validator::extend('validCategories', function ($field, $value, $parameters) {
        $categories = Category::select('id')->get();
        $categoryIds = [];
        foreach($categories as $category) {
          $categoryIds[] = $category->id;
        }
        $validCategories = array_intersect($value, $categoryIds);
        if(count($validCategories) == count($value)) {
          return true;
        }
        return false;
      });
      $rules = [
        'create_type' => 'required|in:'.$createTypesString,
        'type' => 'required|in:'.$typesString,
        'media' => 'required_if:create_type,'.Media::CREATE_TYPE_UPLOAD.'|allowedExt|maxSize',
        'web_link' => 'required_if:create_type,'.Media::CREATE_TYPE_ONLINE.'|url|active_url|max:1000|uniqueMedia:'.$editId.'|unique:media_bans',
        'title' => 'required|max:100',
        'description' => 'required',
        'source' => 'in:'.$sourcesString,
        'user_expertise' => 'in:'.$expertisesString,
        'target_audience' => 'validAudiences',
        'state_id' => 'validState',
        'city' => 'max:100',
        'area' => 'max:100',
        'caption_available' => 'in:1',
        'tags' => 'max:1000',
        'categories' => 'required|validCategories',
      ];
      if($editId) {
        $rules['media'] = '';
        $rules['web_link'] = '';
//        $medium = Media::find($editId);
//        if($medium->file_name) {
//          $rules['media'] = 'allowedExt|maxSize';
//          $rules['web_link'] = 'url|active_url|max:1000|uniqueMedia:'.$editId.'|unique:media_bans';
//        }
      }
      $errorMessages = [
        'web_link.unique' => 'The media is banned.',
        'media.allowed_ext' => 'Please choose a correct file.',
        'media.max_size' => 'Please choose a file with less size. You could upload totally 100MB of files.',
        'target_audience.valid_audiences' => 'Please choose valid target audience.',
        'state_id.valid_state' => 'Please choose a valid state.',
        'categories.valid_categories' => 'Please choose valid categories.'
      ];
      $validator = Validator::make($request->all(), $rules, $errorMessages);
      return $validator;
    }

    /**
     * POST /contribute/add
     * Store new media
     */
    public function store (Request $request) {
      $validator = $this->validateRequest($request);
      if ($validator->fails()) {
        $validator = $this->addUniqueMediaError($validator, $request);
        return redirect('contribute/addForm')->withErrors($validator)->withInput();
      }

      $media = $this->parseRequest($request);
      $media->user_id = Auth::user()->id;
      
      if ($media->save()) {
        if($request->create_type == Media::CREATE_TYPE_UPLOAD) {
          $file = $request->file('media');
          if($file) {
            $media->uploadUploadedFile($file);
          }
        }
        $media->generateThumbnail();
        if ( !is_null($request->categories) ) {
          foreach($request->categories as $categoryId) {
            $category = Category::whereId($categoryId)->first();
            if($category) {
              $media->categories()->attach($categoryId);
            }
          }
        }
        if ( !is_null($request->collections) ) {
          foreach($request->collections as $collectionId) {
              $collection = Collection::whereId($collectionId)->where('user_id', Auth::user()->id)->first();
              if($collection) {
                  CollectionDetail::addMedia($collectionId, $media->id);
              }
          }
        }
        Session::flash('status', 'success');
        Session::flash('message', "Media '{$media->title}' successfully added");
      } else {
        Session::flash('status', 'fail');
      }
      Session::reflash();
      return redirect('/contribute');
    }

    /**
     * POST /media/bulk-send-folder
     * Copy some media to some folders
     */
    public function bulkSendFolder (Request $request) {
      if (is_null(Input::get('folders'))) {
        return back();
      }
      $media = explode(",", Input::get('bulkMedia'));
      if (count($media) === 0 || $media[0] === '') {
        return back();
      }
      foreach ($media as $mediaID) {
        foreach (Input::get('folders') as $collectionID) {
          Media::sendToCollection($mediaID, $collectionID);
        }
      }
      return back();
    }

    /**
     * GET /contribute/{id}/edit
     * Show edit form
     * @param int $id media id
     */
    public function edit($id) {
      return view('beta.media.edit', [
        'countBundleCart' => BundleCart::getBundleCartCount(Auth::user()->id),
        'categories' => Category::ordered()->get(),
        'states' => State::active()->nameOrdered()->get(),
        'collections' => $this->getCollections(),
        'media' => $this->toInputArray($id)
      ]);
    }

    // Parse the media to be used by form
    private function toInputArray($id = null) {
      if ( !is_null($id) ) {
        $media = Media::with('categories', 'collections')->find($id);
        $tags = implode(json_decode($media->tag, true), ', ');
        return [
          'id' => $media->id,
          'title' => $media->title,
          'web_link' => $media->web_link,
          'file_name' => $media->file_name,
          'description' => $media->description,
          'type' => $media->type,
          'source' => $media->source,
          'user_expertise' => $media->user_expertise,
          'target_audience' => $media->target_audience,
          'state_id' => $media->state_id,
          'city' => $media->city,
          'area' => $media->area,
          'language_id' => $media->language_id,
          'caption_available' => $media->caption_available,
          'tags' => $tags,
          'private' => $media->private,
          'categories' => array_pluck($media->categories, 'id'),
          'collections' => array_pluck($media->collections, 'id')
        ];
      }
      return [
        'id' => '',
        'title' => '',
        'web_link' => '',
        'file_name' => '',
        'description' => '',
        'type' => '',
        'source' => null,
        'user_expertise' => null,
        'target_audience' => null,
        'state_id' => null,
        'city' => null,
        'area' => null,
        'language_id' => null,
        'caption_available' => Media::CAPTION_UNAVAILABLE,
        'tags' => '',
        'private' => Media::STATUS_PUBLIC,
        'categories' => [],
        'collections' => []
      ];
    }

    /**
     * POST /contribute/{id}/update
     * Update the media
     * @param int $id media id
     */
    public function update (Request $request, $id) {
      $validator = $this->validateRequest($request, $id);
      if ($validator->fails()) {
        $validator = $this->addUniqueMediaError($validator, $request);
        return redirect("contribute/$id/edit")->withErrors($validator)->withInput();
      }

      $media = $this->parseRequest($request, $id);
      $old_file = $media->old_file;
      unset($media->old_file);
      Session::reflash();
      if ($media->save()) {
        if($request->create_type == Media::CREATE_TYPE_UPLOAD) {
          $file = $request->file('media');
          if($file) {
            $media->uploadUploadedFile($file);
          }
        }
        if ( !is_null($old_file) && $media->file_name !== $old_file ) {
          $media->deleteUploadedFile($old_file);
        }
        $media->categories()->detach();
        if(!is_null($request->categories)) {
          foreach($request->categories as $categoryId) {
            $category = Category::whereId($categoryId)->first();
            if($category) {
              $media->categories()->attach($categoryId);
            }
          }
        }
        CollectionDetail::where('media_id', $media->id)->where('user_id', Auth::user()->id)->delete();
        if(!is_null($request->collections)) {
          foreach($request->collections as $collectionId) {
            $collection = Collection::whereId($collectionId)->where('user_id', Auth::user()->id)->first();
            if($collection) {
              CollectionDetail::addMedia($collectionId, $media->id);
            }
          }
        }
        Session::flash('status', 'success');
        Session::flash('message', "Media '{$media->title}' successfully updated");
      } else {
        Session::flash('status', 'fail');
      }
      return redirect('/contribute');
    }
    
    /**
     * GET /media-document/{id}
     * Show preview media for screenshot
     */
    public function showDocument ($id) {
      $url = '';
      $text = '';
      $className = '';
      
      $media = Media::with('user')->find($id);
      if($media) {
        if (Media::IsUploaded($media)) {
          if (Media::IsFileMS($media)) {
            if (Media::IsFileExt($media, Media::EXT_DOC)) {
              $text = 'WORD <br /> DOCUMENT';
              $className = 'preview-doc';
            } else if (Media::IsFileExt($media, Media::EXT_XLS)) {
              $text = 'EXCEL <br /> DOCUMENT';
              $className = 'preview-xls';
            } else if (Media::IsFileExt($media, Media::EXT_PPT)) {
              $text = 'POWERPOINT <br /> DOCUMENT';
              $className = 'preview-ppt';
            }
            $url = $media->getFileUrl();
          }
        } else if (Media::IsOnline($media)) {
          if (Media::IsLinkExt($media, Media::EXT_DOC)) {
            $url        = $media->web_link;
            $text       = 'WORD <br /> DOCUMENT';
            $className  = 'preview-doc';
          }
        }
        return view('beta.media.show-document', [
          'url' => $url,
          'text' => $text,
          'className' => $className,
        ]);
      }
      // TODO: show no preview available
    }

    /**
     * DELETE /media/{id}
     * Remove the media
     */
    public function delete (Request $request, $id) {
      $media = Media::whereId($id)->where('user_id', Auth::user()->id)->first();
      $success = false;
      if ( !is_null($media) ) {
        $title = $media->title;
        if ( $media->delete() ) {
          $success = true;
          Session::reflash();
          Session::flash('status', 'success');
          Session::flash('message', 'Media '.$title.' has been deleted successfully.');
        }
      }
      if ($request->ajax()) {
        return response()->json(['success' => $success]);
      }
      return back();
    }

    /**
     * GET /media/{id}/like
     * Like the media
     */
    public function like (Request $request, $id) {
      $this->doLike($id, true);
      if ($request->ajax()) {
        return response()->json(Media::find($id)->popularity());
      }
      return back();
    }

    /**
     * GET /media/{id}/dislike
     * Dislike the media
     */
    public function dislike (Request $request, $id) {
      $this->doLike($id, false);
      if ($request->ajax()) {
        return response()->json(Media::find($id)->popularity());
      }
      return back();
    }

    private function doLike ($id, $like) {
      // Make it neutral if user do same action as before (like then like again, or dislike then dislike again)
      $likeDislike = $this->getLikeDislike($id);
      if ( !is_null($likeDislike) ) { // user change action (being neutral or otherwise)
        if (($like && $likeDislike->like == 1) || (!$like && $likeDislike->dislike == 1)) { // being neutral
          LikeDislike::where('id_media', $id)->where('user_id', Auth::user()->id)->delete();
        } else { // different action (from like to dislike or otherwise)
          LikeDislike::where('id_media', $id)
            ->where('user_id', Auth::user()->id)
            ->update(['like' => $like, 'dislike' => !$like]);
        }
      } else {
        $ld = new LikeDislike();
        $ld->user_id  = Auth::user()->id;
        $ld->id_media = $id;
        $ld->like     = $like;
        $ld->dislike  = !$like;
        $ld->save();
      }
    }
    private function getLikeDislike ($id) {
      return LikeDislike::where('id_media', $id)->where('user_id', Auth::user()->id)->first();
    }

    private function _addMediaHistory ($userId, $mediaId) {
      $history = new History;
      $history->user_id  = $userId;
      $history->id_media = $mediaId;
      $history->save();
    }

    /**
     * GET /media/{id}
     * Show media page
     * @param int $id media id
     */
    public function item ($id) {
      $media = Media::qAll()->where('M.id', $id)->get();
      if (count($media) === 0 || ($media[0]->private != Media::STATUS_PRIVATE && $media[0]->private != Media::STATUS_PUBLIC)) {
        abort(404);
      } elseif($media[0]->private == Media::STATUS_PRIVATE) {
        return response()->view('errors.private_media', [], 403);
      } else {
        $auth = false;

        if (Auth::check()) {
          $auth = true;

          //check if the user doesn't same like the creator
          if (Auth::user()->id != $media[0]->user_id) {
            $this->_addMediaHistory(Auth::user()->id, $id);
          }

          //Search if the user like this media or not
          $favorite = DB::select('SELECT * FROM favorite WHERE user_id = ? AND id_media = ?', [Auth::user()->id, $id]);
          $media = Media::fillMediaFields($media, $favorite);
        } else {
          //Search if the user like this media or not
          $media = Media::fillMediaFields($media);
          $this->_addMediaHistory(null, $id);
        }
        if (strpos($media[0]->web_link, 'http') === FALSE &&
            strpos($media[0]->web_link, 'https') === FALSE) {
          $media[0]->web_link = 'http://' . $media[0]->web_link;
        }
        return response()
          ->view($auth ? 'beta.media.item_user' : 'beta.media.item', [
            'media' => $media[0],
            'countBundleCart' => BundleCart::getBundleCartCount(Auth::user()->id),
            'collections' => $this->getCollections(),
            'auth' => $auth
          ])
          ->header('Cache-Control', $this->noCacheControlHeader());
      }
    }

    /**
     * GET /media/ajax/{id}
     * Show partial template of a media (used by ajax call)
     * @param int $id media id
     */
    public function itemAjax ($id) {
      $auth   = false;

      if (Auth::check()) {
        $auth  = true;
        $media = Media::qAll()->where('M.id', $id)->get();
        if (Auth::user()->id != $media[0]->user_id) {
          $this->_addMediaHistory(Auth::user()->id, $id);
        }

        //Search if the user like this media or not
        $favorite = DB::select('SELECT * FROM favorite WHERE user_id = ? AND id_media = ?', [Auth::user()->id, $id]);
        $media = Media::fillMediaFields($media, $favorite);
      } else {
        $media = DB::select(
          "SELECT * FROM media" .
          "   LEFT JOIN (SELECT media_id AS id_media_bundle_cart FROM bundle_cart) AS bundle_cart ON bundle_cart.id_media_bundle_cart = media.id" .
          " WHERE id = ?",
          [$id]
        );
        //Search if the user like this media or not
        $media = Media::fillMediaFields($media);
        $this->_addMediaHistory(null, $id);
      }
      /* debug($media); */
      return view('beta.media.ajaxItem', [
        'media' => $media,
        'auth'  => $auth,
        'countBundleCart' => Auth::check() ? BundleCart::getBundleCartCount(Auth::user()->id) : 0,
        'collections' => $this->getCollections()
      ]);
    }

    /**
     * GET /media/add-to-folder/{media_id}/{folder_id}
     * Add a media to a folder
     * @param int $media_id media id
     * @param int $folder_id folder id
     */
    public function addToFolder (Request $request, $media_id, $folder_id) {
      $collection_detail = new CollectionDetail;
      $collection_detail->user_id = Auth::user()->id;
      $collection_detail->collection_id = $folder_id;
      $collection_detail->media_id = $media_id;
      $collection_detail->save();

      if ($request->ajax()) {
        return response()->json([
          'collections' => $this->_listFolder($media_id)
        ]);
      }
      return back();
    }

    /**
     * GET /media/search?[q=term&except_ids=array_of_ids]
     * search media by terms and exclude except_ids
     */
    public function search (Request $request) {
      $q = $request->input('q');
      $exceptIds = $request->input('except_ids');
      if (is_null($exceptIds)) {
        $exceptIds = [];
      }
      $media = Media::where(function ($query) use ($q) {
          $query->where('title', 'LIKE', "%$q%")
                ->orWhere('description', 'LIKE', "%$q%");
        })
        ->whereNotIn('id', $exceptIds)
        ->get();
      $array = $media->toArray();
      return response()->json($media);
    }

    public function sendToFolder() {

        //dd($_POST);
        // AUTH DATA
        $user_id = Auth::user()->id;
		
		$media_id = Input::get('media_id');
		$collection_arr = Input::get('collection');
		$all_collection_arr = Input::get('all_collection');
		
		if($collection_arr)
		{
			if (isset($all_collection_arr) && count($all_collection_arr) > 0) {
				foreach ($all_collection_arr as $collection_id) {
					
					if (in_array($collection_id, $collection_arr)) {
						if (CollectionDetail::where('collection_id', '=', $collection_id)->where('media_id', '=', $media_id)->where('user_id', '=', $user_id)->count() <= 0) {
							$collection_detail = new CollectionDetail;
							$collection_detail->user_id = $user_id;
							$collection_detail->collection_id = $collection_id;
							$collection_detail->media_id = $media_id;
							$collection_detail->save();
						}
					}
					else
					{
						DB::table('collection_details')->where('user_id', '=', $user_id)->where('collection_id', '=', $collection_id)->where('media_id', '=', $media_id)->delete();
					}
				}
			}
		}
		else
		{
			DB::table('collection_details')->where('user_id', '=', $user_id)->where('media_id', '=', $media_id)->delete();
		}
		
		Session::flash('message', 'Media added to selected folder(s) successfully.');
        return back();
    }


    /**
     * GET /media/{id}/folder
     * List collection with state it's already added into or not
     * @param int $id media id
     */
    public function listFolder ($id) {
      return response()->json([
        'collections' => $this->_listFolder($id)
      ]);
    }
    private function _listFolder ($mediaId) {
      $collections = $this->getCollections();
      for ($i=0 ; $i<count($collections) ; $i++) {
        $collections[$i]->isAdded = CollectionDetail::isCollectionAvailable($mediaId, $collections[$i]->id);
      }
      return $collections;
    }

    /**
     * POST /media/report
     * Report a media
     */
    public function sendReport() {
        $data = [];
        parse_str(Input::get('data'), $data);
        $validator = $this->getReprotValidator($data);
        if ($validator->passes()) {
            $report = $this->saveReport($data);
            $this->sendReprtEmailToAdmin($report);
            return Response::json([
                        'success' => true,
            ]);
        } else {
            return Response::json([
                        'success' => false,
                        'errors' => $validator->messages()
            ]);
        }
    }

    private function getReprotValidator($input) {
        $validationRules = MediaReport::getValidationRules();
        return Validator::make($input, $validationRules);
    }

    private function saveReport($data) {
        $r = new MediaReport;
        $r->media_id = $data['media_id'];
        $r->reason = $data['reason'];
        $r->comment = $data['comment'];
        $r->user_id = Auth::user()->id;
        $r->save();
        return $r;
    }
    
    private function sendReprtEmailToAdmin($reportModel) {
        $mail = Mail::send('emails.mediaReport', ['report' => $reportModel], function ($m) {
            $m->from(config('app.EMAIL_ADDRESS_FROM'), config('app.EMAIL_SUBJECT_FROM'));
            $m->to(config('app.REVIEWER_EMAIL'), config('app.REVIEWER_NAME'))->subject('Media Report');
        });
    }
}
