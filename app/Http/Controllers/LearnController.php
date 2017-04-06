<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Learn;
use App\Models\Media;
use Input,
    Redirect,
    DB,
    Form,
    Auth;

class LearnController extends Controller {
  public function index () {
    $result = DB::select(
      'SELECT learns.sortOrder, learns.id AS id_learn, media.*' .
      ' FROM learns INNER JOIN media ON media.id=learns.id_media' .
      ' ORDER BY learns.sortOrder ASC'
    );
    $media  = Media::fillMediaFields($result);
    return response()
      ->view('admin.learns.index', [
        'page_title' => 'View Learns',
        'media' => $media
      ])
      ->header('Cache-Control', $this->noCacheControlHeader());
  }

  private function _toOptionsForm ($media) {
    $result = array();
    foreach ($media as $medium) {
      $result[(string)$medium->id] = $medium->title;
    }
    return $result;
  }

  public function addNew () {
    $media = DB::select(
      'SELECT * FROM media' .
      ' WHERE id NOT IN (SELECT id_media FROM learns)' .
      '   AND user_id=?',
      [Auth::user()->id]
    );
    return view('admin.learns.new', [
      'page_title' => 'Add Learn',
      'media' => $this->_toOptionsForm($media)
    ]);
  }

  public function create (Request $request) {
    $learn = new Learn;
    $learn->id_media = Input::get('id_media');
    $learn->sortOrder = Input::get('sortOrder');
    $learn->save();

    return Redirect::to('admin/learns')
      ->with('message', 'New Media inserted');
  }

  public function delete (Request $request) {
    $learn = Learn::find(Input::get('id'));
    $learn->delete();
    return Redirect::to('admin/learns')
      ->with('message', 'Media deleted');
  }

  public function edit (Request $request) {
    $learn = Learn::find(Input::get('id'));
    $media = DB::select(
      'SELECT * FROM media' .
      ' WHERE (id NOT IN (SELECT id_media FROM learns) OR id=?)' .
      '   AND user_id=?',
      [$learn->id_media, Auth::user()->id]
    );
    return view('admin.learns.edit', [
      'page_title' => 'Edit Learn',
      'id_media' => $learn->id_media,
      'sortOrder' => $learn->sortOrder,
      'media' => $this->_toOptionsForm($media)
    ]);
  }

  public function update ($id) {
    $learn = Learn::find($id);
    $learn->id_media  = Input::get('id_media');
    $learn->sortOrder = Input::get('sortOrder');
    $learn->save();

    return Redirect::to('admin/learns')
      ->with('message', 'Media updated');
  }
}
