<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LikeDislike extends Model
{
    protected $table = 'like_dislike';

    public function media () {
      return $this->belongsTo('App\Models\Media', 'id_media');
    }

    static public function deleteMedia ($mediaId) {
      return self::where('id_media', $mediaId)->delete();
    }
}
