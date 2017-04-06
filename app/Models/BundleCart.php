<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB,
    Auth;

class BundleCart extends Model {

    protected $table = 'bundle_cart';
    protected $fillable = ['sort_order'];

    public function media () {
      return $this->belongsTo('App\Models\Media', 'media_id', 'id');
    }

    public function user () {
      return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }


    /**
     * Add media id in bulk
     * @param  array $mediumIds
     * @return boolean
     */
    // FIXME: not in sort order
    static public function bulkAddMedia ($mediumIds) {
        ksort($mediumIds);
        foreach ($mediumIds as $sortOrder => $mediumId) {
            self::addMedia($mediumId, $sortOrder);
        }
    }
    
    /**
     * Remove media id in bulk of current user's bundle cart
     * @param  int $mediumIds
     * @return boolean
     */
    static public function bulkRemoveMedia ($mediumIds) {
        self::where('user_id', Auth::user()->id)->whereIn('media_id', $mediumIds)->delete();
        self::resort();
    }
    
    /**
     * Add media id with sort order to current user's bundle cart
     * @param int $mediumId
     * @param int $sortOrder
     * @return boolean
     */
    static public function addMedia($mediumId, $sortOrder = 1) {
        if (self::isInBundle($mediumId)) {
          return false;
        }
        if (is_null(Media::find($mediumId))) {
          return false;
        }
        $cart = new BundleCart();
        $cart->user_id = Auth::user()->id;
        $cart->media_id = $mediumId;
        if (self::isSortOrderTaken($sortOrder)) {
            $cart->sort_order = self::getLastSortOrder() + 1;
        } else {
            $cart->sort_order = $sortOrder;
        }
        $cart->save();
        return $cart;
    }
    
    /**
     * Get last added media sort order number in current users's bundle cart
     * @return int last sort order
     */
    static public function getLastSortOrder () {
        return self::where('user_id', Auth::user()->id)->max('sort_order');
    }
    
    /**
     * Check sort order exists in current users's bundle cart
     * @return boolean true if already exists
     */
    static public function isSortOrderTaken ($sortOrder) {
      return !is_null(self::where('user_id', Auth::user()->id)->where('sort_order', $sortOrder)->first());
    }
    
    /**
     * Check if media is exist in current user's bundle cart
     * @return boolean true if exist
     */
    static public function isInBundle ($mediumId) {
        $record = self::where('user_id', Auth::user()->id)
                ->where('media_id', $mediumId)
                ->first();
        return !is_null($record);
    }

    /**
     * Re-sort media id of current users's bundle cart, counted from 1
     */
    static private function resort () {
      $records = self::where('user_id', Auth::user()->id)->get();
      $sortOrder = 1;
      foreach ($records as $record) {
        $record->update(['sort_order' => $sortOrder]);
        $sortOrder += 1;
      }
    }

    /**
     * Get all bundle list from cart with media
     * Author: Jinandra
     * Date: 22-10-2016
     * @param  string $user_id
     * @return array
     */
    public static function getBundleCartList($user_id) {
        try {
            //Query to get bundle list
            $bundleCartList = DB::table((new BundleCart)->getTable() . ' as bc')
                    ->select('bc.id as cartMediaId', 'bc.sort_order', 'm.title', 'm.id', 'm.description', 'm.type')
                    ->join((new Media)->getTable() . ' as m', 'm.id', '=', 'bc.media_id')
                    ->where('bc.user_id', '=', $user_id)
                    ->orderBy('bc.sort_order', 'ASC')
                    ->get();
            if (!$bundleCartList) {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }

        return $bundleCartList;
    }

    /**
     * Get bundle cart count
     * Author: Jinandra
     * Date: 22-10-2016
     *
     * @param  int $user_id
     * @return data count
     */
    public static function getBundleCartCount($user_id) {
        try {
          $bundleCartCount = self::where('user_id', $user_id)->count();
          if (!$bundleCartCount) {
            return array(0);
          }
        } catch (\Exception $e) {
          return array(0);
        }

        return array($bundleCartCount);
    }

    /**
     * Delete media & restort of current user's bundle cart
     * @param int $mediaId media id
     */
    static public function deleteMedia ($mediaId) {
        if ( Auth::check() ) {
          $result = self::where('media_id', $mediaId)->where('user_id', Auth::user()->id)->delete();
          self::resort();
        } else {
          $result = self::where('media_id', $mediaId)->delete();
        }
        return $result;
    }
}
