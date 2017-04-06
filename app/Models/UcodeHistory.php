<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UcodeHistory extends Model {
    
    protected $table = 'ucode_history';

    public function ucode() {
        return $this->belongsTo('App\Models\Ucode', 'ucode_id', 'id');
    }
}
