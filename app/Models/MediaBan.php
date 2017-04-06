<?php

namespace App\Models;

use DB,
    Auth;
use Illuminate\Database\Eloquent\Model;

class MediaBan extends Model {

    protected $table = 'media_bans';

    static public function getValidationRules() {
        $reasonNumbers = MediaReport::getReasonNumbers();
        return [
            'web_link' => 'required|url|active_url|exists:media,web_link|unique:media_bans|max:1000',
            'reason' => 'required|in:'.$reasonNumbers,
            'user_id' => 'required|exists:users,id',
        ];
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
