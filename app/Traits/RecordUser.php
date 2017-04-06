<?php

namespace App\Traits;

use Auth;

trait RecordUser {
  public function createdBy () {
    return $this->belongsTo('App\Models\User', 'created_by');
  }

  public function updatedBy () {
    return $this->belongsTo('App\Models\User', 'updated_by');
  }

  static protected function bootRecordUser () {
    static::saving(function ($model) {
      if ($model->exists) {
        $model->updated_by = Auth::user()->id;
      } else {
        $model->created_by = Auth::user()->id;
      }
    });
  }
}
