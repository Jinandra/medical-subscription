<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordUser;
use Auth;

class Category extends Model
{
  use RecordUser;

  protected $table = 'categories';
  protected $fillable = ['name', 'description'];

  public function scopeOrdered ($query) {
    return $query->orderBy('name', 'ASC');
  }


  public function updatedAtFromNow () {
    $date = new Date($this->updated_at);
    return $date->ago();
  }

  public function users () {
    return $this->belongsToMany('App\Models\User', 'category_user', 'category_id', 'user_id');
  }

  public function media ($orderMethod = 'ASC') {
    return $this->belongsToMany('App\Models\Media', 'categories_media', 'category_id', 'media_id')
      ->withTimestamps()
      ->withPivot('sort_order', 'id', 'updated_at')
      ->orderBy('sort_order', isset($orderMethod) ? $orderMethod : 'ASC');
  }

  public function getNextMediaSortOrder () {
    $medium = $this->media('DESC')->limit(1)->first();
    if (is_null($medium)) {
      return 1;
    }
    return $medium->pivot->sort_order + 1;
  }

  public function collections () {
    return $this->hasMany('App\Models\Collection', 'category_id');
  }

  static protected function boot () {
    parent::boot();
    static::deleting(function ($category) {
      $category->media()->detach();
      $category->users()->detach();
      foreach ($category->collections as $collection) {
        $collection->destroy();
      }
    });
    static::updated(function ($category) {
      foreach ($category->collections as $collection) {
        $collection->name = $category->name;
        $collection->description = $category->description;
        $collection->save();
      }
    });
  }
}
