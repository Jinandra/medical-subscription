<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Learn extends Model {
  protected $table = 'learns';

  static public function deleteMedia ($mediaId) {
    return self::where('id_media', $mediaId)->delete();
  }
}
