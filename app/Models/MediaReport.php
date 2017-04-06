<?php

namespace App\Models;

use DB,
    Auth;
use Illuminate\Database\Eloquent\Model;

class MediaReport extends Model {

    protected $table = 'media_reports';

    const REASON_INAPPROPRIATE = 1;
    const REASON_COPYRIGHTED = 2;
    const REASON_INACCURATE = 3;
    const REASON_OTHER = 4;
    
    static public function getValidationRules() {
        $reasonNumbers = self::getReasonNumbers();
        return [
            'media_id' => 'required|exists:media,id',
            'reason' => 'required|in:'.$reasonNumbers,
            'comment' => 'max:500',
        ];
    }

    static public function getReasonNumbers() {
        $numbers = '';
        $reasons = self::getAllReasons();
        foreach ($reasons as $number => $text) {
            $numbers .= $number . ',';
        }
        return substr_replace($numbers, "", -1);
    }

    static public function getAllReasons() {
        return [
            self::REASON_INAPPROPRIATE => 'Inappropriate Media',
            self::REASON_COPYRIGHTED => 'Copyrighted Content',
            self::REASON_INACCURATE => 'Inaccurate Content',
            self::REASON_OTHER => 'Other',
        ];
    }

    static public function getReasonTextByNumber($n) {
        $reasons = self::getAllReasons();
        if (array_key_exists($n, $reasons)) {
            return $reasons[$n];
        }
        return '';
    }

    public function media() {
        return $this->belongsTo('App\Models\Media', 'media_id', 'id');
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    static public function deleteMedia ($mediaId) {
      return self::where('media_id', $mediaId)->delete();
    }
}
