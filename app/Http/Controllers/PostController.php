<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \Cviebrock\EloquentSluggable\Services\SlugService;
use Session;
use App\Models\Post;
use App\Models\BundleCart;
use Input,
    Validator,
    Redirect,
    Hash,
    DB,
    Auth,
    Mail,
    Form;

class PostController extends Controller {

    public function firstPost (Request $request) {
      $post = Post::where('post_status', 'Publish')
        ->orderBy('sort_order', 'ASC')
        ->first();
      if (is_null($post)) {
        abort(404);
      } else {
        return redirect("post/{$post->slug}");
      }
    }

    /**
     * {GET} Single Post Page
     */
    public function singlePost(Request $request) {
        $post = Post::getBySlug($request->slug);
        $data['post'] = Post::getBySlug($request->slug);
        if ( $data['post'] ) {
          $viewData = [
            'auth'=>Auth::check(),
            'post' => $post,
            'posts' => Post::where('post_status', 'Publish')->orderBy('sort_order', 'ASC')->get(),
          ];
          if(Auth::check()) {
            $viewData['countBundleCart'] = BundleCart::getBundleCartCount(Auth::user()->id);
          }
          return response()->view('beta.post', $viewData)->header('Cache-Control', $this->noCacheControlHeader());
        } else {
            abort(404);
        }
    }

    /*     * ******** ADMIN *********************************************************** */

    /**
     * {GET} View All Post
     */
    public function allPost(Request $request) {
        $data['page_title'] = "View Posts";

        $data['post'] = Post::where(function($query) {
                    $query->where('title', 'like', '%' . Input::get('s') . '%');
                    $query->orWhere('content', 'like', '%' . Input::get('s') . '%');
                    $query->orWhere('category', 'like', '%' . Input::get('s') . '%');
                })->orderBy('sort_order', 'asc')
                ->paginate(10);

        return view('admin.post.view_post', $data);
    }

    /**
     * {GET/POST} Add New Post
     */
    public function addNew(Request $request) {
        $data['page_title'] = "Add New Post";

        if (Input::get('submit')) {

            $rules = array(
                'title' => 'required:max:200|min:5',
                'content' => 'required|min:5',
                'category' => 'required',
                'post_status' => 'required',
                'image' => 'image|max:3072',
            );

            $validator = Validator::make(Input::all(), $rules);

            if ($validator->fails()) {
                return Redirect::back()
                                ->withErrors($validator)
                                ->withInput();
            } else {
                $post = new Post;

                if (Input::hasFile('image')) {
                    $file = Input::file('image');
                    $destinationPath = 'upload/post/';
                    $filename = str_random(20) . '.' . $file->getClientOriginalName();
                    $upload_success = $file->move($destinationPath, $filename);
                    $post->image = $destinationPath . $filename;
                }

                $post->title = Input::get('title');
                $post->content = Input::get('content');
                $post->post_status = Input::get('post_status');
                $post->category = Input::get('category');
                $post->sort_order = Post::getNewOrder();

                $post->save();

                return Redirect::to('admin/post')
                                ->with('message', 'New Post Inserted.');
            }
        }

        return view('admin.post.add_new_post', $data);
    }

    /**
     * {GET/POST} Edit Post
     */
    public function editPost(Request $request) {
        $data['page_title'] = "Edit Post";
        $data['post'] = Post::find(Input::get('id'));

        if (Input::get('submit')) {

            $rules = array(
                'title' => 'required:max:200|min:5',
                'content' => 'required|min:5',
                'category' => 'required',
                'post_status' => 'required',
                'image' => 'image|max:3072',
            );

            $validator = Validator::make(Input::all(), $rules);

            if ($validator->fails()) {
                return Redirect::back()
                                ->withErrors($validator)
                                ->withInput();
            } else {
                $post = Post::find(Input::get('id'));

                if (Input::hasFile('image')) {
                    $file = Input::file('image');
                    $destinationPath = 'upload/post/';
                    $filename = str_random(20) . '.' . $file->getClientOriginalName();
                    $upload_success = $file->move($destinationPath, $filename);
                    $post->image = $destinationPath . $filename;

                    @unlink(Input::get('curr_image'));
                }

                if (Input::get('title') != Input::get('curr_title')) {
                    $post->slug = SlugService::createSlug(Post::class, 'slug', Input::get('title'));
                }

                $post->title = Input::get('title');
                $post->content = Input::get('content');
                $post->post_status = Input::get('post_status');
                $post->category = Input::get('category');

                $post->save();

                return Redirect::to('admin/post')->with('message', 'Post Updated.');
            }
        }

        return view('admin.post.edit_post', $data);
    }

    /**
     * {GET/POST} Delete Post
     */
    public function deletePost(Request $request) {
        if (Session::token() != Input::get('_token')) {
            $data['page_title'] = "Something Wrong";
            $data['message'] = "Invalid CSRF Token! Please Back to previous page and reload the page.";
            return view('admin.error', $data);
        } else {
            if (Input::get('id') != '') {

                $post = Post::find(Input::get('id'));
                @unlink('upload/post/' . $post->image);
                $post->delete();

                return Redirect::to('/admin/post')
                                ->with('message', 'Post Deleted.');
            } else {
                $data['page_title'] = "Something Wrong";
                $data['message'] = "Invalid Action Request! Please Back to previous page and reload the page.";
                return view('admin.error', $data);
            }
        }

        return Redirect::to('/admin/post');
    }

    private function _sort (Request $request) {
      $post_ids = $request->input('post_ids');
      if (is_null($post_ids)) {
        $post_ids = [];
      }
      foreach ($post_ids as $index => $id) {
        $post = Post::find($id);
        $post->sort_order = $index;
        $post->save();
      }
      if ($request->ajax()) {
        return response()->json($post_ids);
      }
      return back();
    }

    public function patchPosts (Request $request) {
      switch ($request->input('action')) {
        case 'sort':
          return $this->_sort($request);
          break;
        default:
          if ($request->ajax()) {
            return response()->json([ 'error' => 'invalid action' ]);
          }
          return back();
      }
    }

}
