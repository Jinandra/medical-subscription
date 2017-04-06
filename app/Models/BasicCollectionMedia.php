<?php

namespace App\Models;

use DB,
    Auth;

use Illuminate\Database\Eloquent\Model;


class BasicCollectionMedia extends Model {
  protected $table = 'basic_collection_media';


  public function media () {
    return $this->belongsTo('App\Models\Media');
  }

  public function user () {
    return $this->belongsTo('App\Models\User', 'created_by');
  }

  static public function getLastSortOrder () {
    $sortOrder = self::orderBy('sort_order', 'desc')->limit(1)->value('sort_order');
    return is_null($sortOrder) ? 1 : $sortOrder+1;
  }

  static protected function boot () {
    parent::boot();
    static::saving (function ($record) {
      $record->user()->associate(User::find(Auth::user()->id));
    });
  }


  // SERIALIZATION
  //
  protected $appends = ['updated_at_formatted'];

  public function getUpdatedAtFormattedAttribute () {
    return time_ago($this->attributes['updated_at']);
  }
}
