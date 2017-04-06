<?php

namespace App\Models;

use DB,
    Auth;

use Illuminate\Database\Eloquent\Model;


class PseudoCollection extends Model {

  protected $table = 'pseudo_collections';

  const ID_HISTORY      = 'history';
  const ID_LIKED        = 'liked';
  const ID_BOOKMARKED   = 'bookmarked';
  const ID_CONTRIBUTED  = 'contributed';
  const ID_BASIC        = 'basic';

  static public function getDescription ($name) {
    switch ($name) {
    case self::ID_HISTORY:
      return 'History';
    case self::ID_LIKED:
      return 'Liked Media';
    case self::ID_BOOKMARKED:
      return 'Bookmark';
    case self::ID_CONTRIBUTED:
      return 'Contribution';
    case self::ID_BASIC:
      return 'Basic';
    }
    return '';
  }

  static public function listPinned () {
    return self::where('user_id', Auth::user()->id)
      ->where('is_pin', true)
      ->get();
  }

  static public function listAll () {
    $names  = array(
      self::ID_HISTORY,
      self::ID_LIKED,
      self::ID_BOOKMARKED,
      self::ID_CONTRIBUTED,
      self::ID_BASIC
    );
    $array = array();
    foreach ($names as $name) {
      $array[] = self::findOrCreate($name);
    }
    return collect($array);
  }

  // use name instead of id
  static public function findOrCreate ($name) {
    $collection = self::where('name', $name)
      ->where('user_id', Auth::user()->id)
      ->first();
    if (is_null($collection)) {
      $collection = new PseudoCollection;
      $collection->name = $name;
      $collection->description = self::getDescription($name);
      $collection->is_pin = false;
      $collection->user_id = Auth::user()->id;
      $collection->save();
    }
    return $collection;
  }

  public function hasParent () {
    return false;
  }

  public function isCategory () {
    return false;
  }

  public function isOriginal () { // Compatibility to collection model
    return false;
  }

  public function media () {
    switch ($this->name) {
    case self::ID_HISTORY:
      return self::_listHistory();
    case self::ID_LIKED:
      return self::_listLiked();
    case self::ID_BOOKMARKED:
      return self::_listBookmarked();
    case self::ID_CONTRIBUTED:
      return self::_listContributed();
    case self::ID_BASIC:
      return self::_listBasic();
    }
  }

  static private function _listHistory () {
    return History
      ::where('user_id', Auth::user()->id)
      ->with('media')
      ->groupBy('id_media')
      ->orderBy('updated_at', 'DESC')
      ->take(15)
      ->get()
      ->map(self::mapMedia())
      ->reject(self::isNull());
  }

  static public function deleteHistory ($mediaIds) {
    return History::whereIn('id_media', $mediaIds)
      ->where('user_id', Auth::user()->id)
      ->delete();
  }

  static public function deleteBookmarked ($mediaIds) {
    return Favorite::whereIn('id_media', $mediaIds)
      ->where('user_id', Auth::user()->id)
      ->delete();
  }

  static public function deleteLiked ($mediaIds) {
    return LikeDislike::whereIn('id_media', $mediaIds)
      ->where('user_id', Auth::user()->id)
      ->delete();
  }

  static public function _listBasic () {
    return BasicCollectionMedia
      ::with('media')
      ->orderBy('sort_order')
      ->get()
      ->map(self::mapMedia())
      ->reject(self::isNull());
  }

  static private function _listBookmarked () {
    return Favorite
      ::where('user_id', Auth::user()->id)
      ->with('media')
      ->groupBy('id_media')
      ->orderBy('updated_at', 'desc')
      ->take(15)
      ->get()
      ->map(self::mapMedia())
      ->reject(self::isNull());
  }

  static private function _listContributed () {
    return Media
      ::where('user_id', Auth::user()->id)
      ->groupBy('id')
      ->orderBy('updated_at', 'desc')
      ->get()
      ->reject(self::isNull());
  }

  static private function _listLiked () {
    return LikeDislike
      ::with('media')
      ->where('user_id', Auth::user()->id)
      ->where('like', true)
      ->groupBy('id_media')
      ->orderBy('updated_at', 'desc')
      ->take(15)
      ->get()
      ->map(self::mapMedia())
      ->reject(self::isNull());
  }

  static private function mapMedia () {
    return function ($relation) {
      return $relation->media;
    };
  }
  static private function isNull () {
    return function ($object) {
      return is_null($object);
    };
  }


  static private function _loadMedia ($relations) {
    $result = new \Illuminate\Database\Eloquent\Collection;
    foreach ($relations as $relation) {
      $id = $relation->id_media;
      if (is_null($id)) {
        $id = $relation->media_id;
      }
      $media = Media::find($id);
      if ( !is_null($media) ) {
        $result->add($media);
      }
    }
    return $result;
  }
}
