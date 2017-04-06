<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailHistory extends Model
{
    protected $table = 'email_history';
    
    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
    
    public function ucode() {
        return $this->belongsTo('App\Models\Ucode', 'ucode_id', 'id');
    }
}
