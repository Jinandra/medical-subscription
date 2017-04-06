<?php

namespace App\Models;

use DB,
    Auth;
use Illuminate\Database\Eloquent\Model;

class State extends Model {

    protected $table = 'states';
    protected $fillable = ['full_name', 'short_name', 'country_id', 'status'];
    
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    
    public function scopeActive($query) {
      return $query->where('status', self::STATUS_ACTIVE);
    }
    
    public function scopeInactive($query) {
      return $query->where('status', self::STATUS_INACTIVE);
    }
    
    public function scopeNameOrdered($query) {
      return $query->orderBy('full_name', 'ASC');
    }
}
