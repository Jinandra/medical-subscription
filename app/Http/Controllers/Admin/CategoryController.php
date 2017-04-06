<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Media;

use Validator,
    Form,
    Input,
    Redirect;

class CategoryController extends Controller
{
  // GET /
  // display all categories
  public function index (Request $request) {
    $s = $request->input('s');
    $data = [
      'page_title' => 'Categories',
      'categories' => Category
        ::where(function ($query) use ($s) {
          $query->where('name', 'LIKE', "%{$s}%")
            ->orWhere('description', 'LIKE', "%{$s}%");
        })
        ->ordered()
        ->get()
    ];
    return view('admin.category.index', $data);
  }

  // GET /new
  // display form to add new category
  public function addNew () {
    $data = [
      'page_title' => 'Add New Category'
    ];
    return view('admin.category.new', $data);
  }

  // POST /
  // create new collection
  public function store () {
    $validator = $this->_validateCategory();
    if ($validator->fails()) {
      return Redirect::back()
        ->withErrors($validator)
        ->withInput();
    } else {
      $category = Category::create(Input::all());
      return Redirect::to('admin/categories')
        ->with('message', "Category {$category->name} inserted.");
    }
  }

  // validate category data
  private function _validateCategory ($id = null) {
    $unique = "unique:categories,name";
    if ( !is_null($id) ) {
      $unique = "{$unique},${id},id";
    }
    $rules = array(
      'name' => "required:max:254|min:5|{$unique}"
    );
    $validator = Validator::make(Input::all(), $rules);
    return $validator;
  }

  // GET /{id}/edit
  // display form to edit category data
  public function edit ($id) {
    $data = [
      'page_title' => 'Edit Category',
      'category' => Category::find($id)
    ];
    return view('admin.category.edit', $data);
  }

  // PATCH /{id}
  // update a category data
  public function update ($id) {
    $validator = $this->_validateCategory($id);
    if ($validator->fails()) {
      return Redirect::back()
        ->withErrors($validator)
        ->withInput();
    } else {
      $category = Category::find($id);
      $category->update(Input::all());

      return Redirect::to('admin/categories')
        ->with('message', "Category {$category->name} updated.");
    }
  }

  // DELETE /{id}
  // delete a category
  public function delete ($id) {
    $category = Category::find($id);
    $category->destroy();
    return back()->with('message', 'Category '.$category->name.' deleted.');
  }


  // GET /{id}/media
  // display category's media
  // id => category id
  public function media ($id) {
    $category = Category::find($id);
    $data = [
      'page_title' => 'Edit Category Media',
      'category' => $category,
      'media' => $category->media()
        ->where(function ($query) {
          $query->where('title', 'LIKE', '%'.Input::get('s').'%')
            ->orWhere('description', 'LIKE', '%'.Input::get('s').'%');
        })
        ->orderBy('sort_order')
        ->get()
    ];
    return view('admin.category.media', $data);
  }


  // POST /{id}/media
  // create new category's media
  // id => category id
  public function storeMedia (Request $request, $id) {
    $category = Category::find($id);
    $mediaId  = $request->input('media_id');
    $category->media()->attach($mediaId, ['sort_order' => $category->getNextMediaSortOrder()]);
    // Sync collection details, TODO: move to event based
    foreach ($category->collections as $collection) {
      $collection->media()->attach($mediaId, ['sort_order' => $collection->getNextMediaSortOrder(), 'user_id' => $collection->user_id]);
    }
    $media = $category->media()->where('media_id', $mediaId)->first();
    if ($request->ajax()) {
      return response()->json(array_merge(
        ['media' => $media],
        ['pivot_id' => $media->pivot->id, 'pivot_updated_at_formatted' => time_ago($media->pivot->updated_at)]
      ));
    }
    return back()->with('message', "Media {$media->title} added into {$category->name}");
  }


  // DELETE /{categoryId}/media/{mediaId}
  // delete category's media
  public function deleteMedia (Request $request, $categoryId, $mediaId) {
    $category = Category::find($categoryId);
    $category->media()->detach($mediaId);
    // Sync collection details, TODO: move to event based
    foreach ($category->collections as $collection) {
      $collection->media()->detach($mediaId);
    }
    if ($request->ajax()) {
      return response()->json(['category_id' => $categoryId, 'media_id' => $mediaId]);
    }
    $media = Media::find($mediaId);
    return back()->with('message', "Media {$media->title} removed from {$category->name}");
  }

  private function _sort (Request $request, $categoryId) {
    $ids = $request->input('media_ids');
    $category = Category::find($categoryId);
    if ( is_array($ids) ) {
      $sortOrder = 1;
      foreach ($ids as $id) {
        $category->media()->updateExistingPivot($id, ['sort_order' => $sortOrder]);
        $sortOrder += 1;
      }
    }
    if ($request->ajax()) {
      return response()->json([
        'media_ids' => $ids,
        'last_modified' => time_ago(date("Y-m-d H:i:s"))
      ]);
    }
    return back();
  }

  // PATCH /{id}/media
  // update category's media
  // action: sort
  public function updateMedia (Request $request, $categoryId) {
    $action = $request->input('action');
    switch ($action) {
    case 'sort':
      return $this->_sort($request, $categoryId);
    }
    if ($request->ajax()) {
      return response()->json(['error' => 'invalid action '.$action]);
    }
    return back();
  }
}
