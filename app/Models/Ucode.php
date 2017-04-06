<?php

namespace App\Models;

use DB,
    Auth;
use Illuminate\Database\Eloquent\Model;

class Ucode extends Model
{
    protected $table = 'ucode';

    public function details () {
        return $this->hasMany('App\Models\DetailUcode', 'ucode_id', 'id');
    }

    public function emailHistories () {
        return $this->hasMany('App\Models\EmailHistory', 'ucode_id', 'id');
    }

    public function textHistories () {
        return $this->hasMany('App\Models\TextHistory', 'ucode_id', 'id');
    }

    public function histories () {
        return $this->hasMany('App\Models\UcodeHistory', 'ucode_id', 'id');
    }

    public function media () {
      return $this->belongsToMany('App\Models\Media', 'detail_ucode', 'ucode_id', 'id_media');
    }

    static public function MyUcodes () {
      return self::with('media')
        ->where('user_id', Auth::user()->id)
        ->get();
    }

    public function listMediaNotInBundle () {
      $mediaIds = Auth::user()->bundles->map(function ($bundle) {
        return $bundle->media->id;
      })->toArray();
      return details()
        ->whereNotIn('id_media', $mediaIds)
        ->orderBy('sort_order', 'ASC')
        ->orderBy('id', 'DESC')
        ->get();
    }


    /**
     * Get ucode
     * Author: Jinandra
     * Date: 19-12-2016
     *
     * @param  string $ucode
     * @return array
     */
    public static function getStatusBundle () {
      $tbnUcode = (new Ucode)->getTable();
      $tbnDetailUcode = (new DetailUcode)->getTable();
      $tbnBundleCart  = (new BundleCart)->getTable();
      return DB::table($tbnUcode.' as UC')
        ->select('UC.ucode', 'DU.countOnUcode', 'BU.countOnBundle')
        // total media in a ucode
        ->leftJoin(DB::raw('(SELECT ucode_id, COUNT(*) AS countOnUcode FROM '.$tbnDetailUcode.' GROUP by ucode_id) as DU'), 'DU.ucode', '=', 'UC.ucode')
        // total media in a ucode that currently in bundle
        ->leftJoin(DB::raw('(SELECT ucode_id, COUNT(*) AS countOnBundle FROM '.$tbnDetailUcode.' WHERE id_media IN (SELECT media_id FROM '.$tbnBundleCart.' WHERE user_id='.Auth::user()->id.') GROUP BY ucode_id) as BU'), 'BU.ucode_id', '=', 'UC.id')
        ->where('UC.user_id', '=', Auth::user()->id)
        ->get();
    }


    /**
     * Normalizes ucode string
     * @param string $ucode a ucode
     * @return string normalized ucode
     */
    static public function normalize ($ucode) {
      if ( strlen($ucode) === 10 ) {
        return substr($ucode, 0, 5)."-".substr($ucode, 5, 5);
      }
      if ( strlen($ucode) === 16 ) { // old format
        return substr($ucode, 0, 4)."-".substr($ucode, 4, 4)."-".substr($ucode, 8, 4)."-".substr($ucode, 12, 4);
      }
      return $ucode;
    }

    /**
     * Returns ucode's media and its statistic
     * @return Array array of media and its stats
     */
    public function statsMedia () {
      $rows =
        DB::table((new DetailUcode)->getTable().' as DUC')
          ->select('M.*')
          ->join(DB::raw('( '.Media::qAll(true)->toSql().' ) as M'), 'M.id', '=', 'DUC.id_media')
          ->join((new Ucode)->getTable().' as UCO', 'UCO.id', '=', 'DUC.ucode_id')
          ->where('UCO.id', '=', $this->id)
          ->orderBy('DUC.sort_order', 'ASC')
          ->orderBy('DUC.id', 'DESC')
          ->get();
      return Media::fillMediaFields($rows);
    }

    static protected function boot () {
      parent::boot();
      static::deleting (function ($ucode) {
        $ucode->details()->detach();
        $ucode->histories()->detach();
        $ucode->emailHistories()->detach();
        $ucode->textHistories()->detach();
      });
    }

    /**
     * Find by ucode
     * @param string $ucode
     * @return Ucode ucode object
     */
    static public function findByUcode ($ucode) {
      return self::where('ucode', self::normalize($ucode))->first();
    }
}
