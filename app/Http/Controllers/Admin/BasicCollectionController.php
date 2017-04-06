<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BasicCollectionMedia;
use App\Models\Media;

use Validator,
    Form,
    Input,
    Auth,
    Redirect;

class BasicCollectionController extends Controller
{
  // GET /
  public function index () {
    $data = [
      'page_title' => 'Basic Collection Media',
      'media' => BasicCollectionMedia::whereHas('media', function ($query) {
        $query->where('title', 'LIKE', '%'.Input::get('s').'%')
              ->orWhere('description', 'LIKE', '%'.Input::get('s').'%');
      })
      ->orderBy('sort_order')
      ->get()
    ];
    return view('admin.basiccollection.index', $data);
  }

  private function _sort(Request $request) {
    $mediaIds = $request->input('media_ids');
    if (isset($mediaIds)) {
      $sortOrder = 1;
      foreach ($mediaIds as $id) {
        $m = BasicCollectionMedia::find($id);
        $m->sort_order = $sortOrder;
        $m->save();
        $sortOrder += 1;
      }
    }
    if ($request->ajax()) {
      return response()->json([
        'media_ids' => $mediaIds,
        'screen_name' => Auth::user()->screen_name,
        'last_modified' => time_ago(date("Y-m-d H:i:s"))
      ]);
    }
    return back();
  }

  // PATCH /
  public function update (Request $request) {
    $action = $request->input('action');
    switch ($action) {
    case 'sort':
      return $this->_sort($request);
    }
    if ($request->ajax()) {
      return response()->json(['error' => 'invalid action '.$action]);
    }
    return back();
  }

  public function store (Request $request) {
    $object = new BasicCollectionMedia();
    $object->media()->associate(Media::find($request->input('media_id')));
    $object->sort_order = BasicCollectionMedia::getLastSortOrder();
    $object->save();
    if ($request->ajax()) {
      return response()->json($object->toArray());
    }
    return back();
  }


  // DELETE /:id
  public function delete (Request $request, $id) {
    $m = BasicCollectionMedia::find($id);
    $m->delete();
    if ($request->ajax()) {
      return response()->json(['deleted' => $id]);
    }
    return back();
  }
}
