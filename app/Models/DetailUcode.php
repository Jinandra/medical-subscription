<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB,
    Auth;
    
class DetailUcode extends Model
{
    protected $table = 'detail_ucode';
    protected $fillable = ['ucode_id', 'id_media', 'sort_order'];

    public function ucode () {
      return $this->belongsTo('App\Models\Ucode', 'ucode_id', 'id');
    }

    public function media () {
      return $this->belongsTo('App\Models\Media', 'id_media', 'id');
    }

    /* delete media from table */
    static public function deleteMedia ($mediaId) {
      return self::where('id_media', $mediaId)->delete();
    }

}
