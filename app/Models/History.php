<?php

namespace App\Models;

use DB,
    Auth;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
  protected $table = 'history';

  public function media () {
    return $this->belongsTo('App\Models\Media', 'id_media');
  }

  static public function latest ($limit = 15) {
    return History
      ::where('user_id', Auth::user()->id)
      ->with('media')
      ->groupBy('id_media')
      ->orderBy('updated_at', 'DESC')
      ->take(15)
      ->get()
      ->map(function ($history) {
        return $history->media;
      });
  }

  static public function deleteMedia ($mediaId) {
    return self::where('id_media', $mediaId)->delete();
  }
}
