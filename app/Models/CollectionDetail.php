<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB,
    Auth;

use App\Models\Media;
use App\Models\Collection;

class CollectionDetail extends Model
{
    protected $table = 'collection_details';


    static public function deleteCollectionOf ($collectionId) {
      // Delete copied folder as welll
      foreach (Collection::find($collectionId)->children as $child) {
        self::deleteCollectionOf($child->id);
      }
      return self::where('collection_id', $collectionId)->delete();
    }

    static public function hasMedia ($collectionId, $mediaId) {
      $record = self::where('collection_id', $collectionId)
        ->where('media_id', $mediaId)
        ->first();
      return !is_null($record);
    }
    
    static public function findMediaOrderByCollectionId ($collectionId, $mediaId) {
        $mediaSortDetail = DB::table((new CollectionDetail)->getTable().' as CM')
                     ->select('CM.sort_order')
                     ->where('CM.collection_id', '=', $collectionId)
                     ->where('CM.media_id', '=', $mediaId)
                     ->first();
        if( $mediaSortDetail ){
            return $mediaSortDetail;
        }else{
            return false;
        }
    }

    static public function addMedia ($collectionId, $mediaId, $userId = null) {
      if ( self::hasMedia($collectionId, $mediaId) ) {
        return false;
      }
      $collection = Collection::find($collectionId);
      $collection->media()->attach($mediaId, [
        'user_id'    => is_null($userId) ? Auth::user()->id : $userId,
        'sort_order' => $collection->getNextMediaSortOrder()
      ]);
      // Add copied on copied folder as well
      foreach ($collection->children as $child) {
        self::addMedia($child->id, $mediaId, $child->user_id);
      }

      return $collection->media()->where('media_id', $mediaId)->first();
    }

    static public function deleteMedia ($mediaId) {
      return self::where('media_id', $mediaId)->delete();
    }
	
	public static function isCollectionAvailable($media_id, $folder_id)
	{
		// AUTH DATA
		$user_id = Auth::user()->id;
		
		$result = DB::select('select * from collection_details where user_id='.$user_id.' and collection_id='.$folder_id.' and media_id='.$media_id);
		return count($result);
        //return $media;
	}
	
	public static function getMediaList($folder_id)
	{
		// AUTH DATA
		$user_id = Auth::user()->id;
		$result = DB::select('select *, c.id as media_col_id from collection_details c, media m where c.media_id=m.id and (c.user_id='.$user_id.' and c.collection_id='.$folder_id.') order by c.updated_at desc');
		
		return $result;
	}
	
	public static function addedToList($media_id)
	{
		$result = DB::select("select * from collection_details where media_id='$media_id'");
		return count($result);
	}
	
	public static function getFirstMediaFolder($folder_id)
	{
		$q = "select *, c.id as media_col_id from collection_details c, media m where c.media_id=m.id and c.collection_id='$folder_id' order by c.updated_at desc limit 0,1";
		$all = DB::select($q);
		
		if(isset($all) && count($all) > 0)
		{
			$result = Media::fillMediaFields($all);
			return $result['0'];
		}
	}

}
