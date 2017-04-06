<?php

namespace App\Models;

use DB,
    Auth;

use App\Models\CollectionDetail;
/* use App\Models\CollectionDetails; */

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $table = 'collections';
    protected $fillable = ['user_id', 'private', 'name', 'description', 'original_id', 'category_id', 'is_pin'];
    
    public function user() {
      return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function parent () {
      return $this->belongsTo('App\Models\Collection', 'original_id', 'id');
    }

    public function hasParent () {
      return !is_null($this->parent);
    }

    public function category () {
      return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function isCategory () {
      return !is_null($this->category);
    }

    public function isOriginal () {
      return !$this->hasParent() && !$this->isCategory();
    }

    public function children () {
      return $this->hasMany('App\Models\Collection', 'original_id');
    }

    public function hasChildren () {
      return $this->children->count() > 0;
    }

    public function media ($orderMethod = 'ASC') {
      return $this->belongsToMany('App\Models\Media', 'collection_details', 'collection_id', 'media_id')
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

    static public function listSaved () { // saved
      return Auth::user()->savedFolders();
    }

    static public function listMine () {  // created
      return Auth::user()->createdFolders();
    }

    static public function listCategoried () { // from category
      return Auth::user()->categoriedFolders();
    }

    static public function listPinned () {
      $collections = self::where('user_id', Auth::user()->id)
        ->where('is_pin', true)
        ->get();
      return $collections->merge(PseudoCollection::listPinned());
    }


    // media => array of medium_id in sorted
    static public function resort ($id, $media) {
      $sortOrder = 1;
      foreach ($media as $medium_id) {
        DB::table('collection_details')
          ->where('media_id', $medium_id)
          ->where('collection_id', $id)
          ->where('user_id', Auth::user()->id)
          ->update(['sort_order' => $sortOrder]);
        $sortOrder += 1;
      }
    }

    static public function isPseudo ($id) {
      return in_array($id, [
        PseudoCollection::ID_HISTORY,
        PseudoCollection::ID_LIKED,
        PseudoCollection::ID_BOOKMARKED,
        PseudoCollection::ID_CONTRIBUTED,
        PseudoCollection::ID_BASIC
      ]);
    }

    static public function findExtended ($id) {
      if (self::isPseudo($id)) {
        $collection = PseudoCollection::findOrCreate($id);
        return $collection;
      }
      return self::find($id);
    }

    public function getMedia () {
      return self::isPseudo($this->id) ? $this->media() : $this->media;
    }

    static public function deleteExtended ($id) {
      return self::destroy($id);
    }

    static public function deleteMedia ($collectionId, $mediumIds) {
      $collection = self::find($collectionId);

      // Delete copied folder as well
      foreach ($collection->children as $child) {
        self::deleteMedia($child->id, $mediumIds);
      }
      return CollectionDetail::where('collection_id', $collectionId)
        ->whereIn('media_id', $mediumIds)
        ->delete();
    }

    static public function copyMedia ($mediumIds, $targetCollectionIds) {
      foreach ($mediumIds as $mediumId) {
        foreach ($targetCollectionIds as $collectionId) {
          self::addMedia($collectionId, $mediumId);
        }
      }
    }

    static public function copyCollection ($collectionId, $targetCollectionIds) {
      $collection = self::findExtended($collectionId);
      foreach ($collection->getMedia() as $medium) {
        foreach ($targetCollectionIds as $collectionId) {
          self::addMedia($collectionId, $medium->id);
        }
      }
    }

    static public function addMedia ($collectionId, $mediaId) {
      $collection = self::find($collectionId);
      if ( !$collection->isOriginal() ) {   // Don't add media on copied/categoried folder
        return false;
      }
      return CollectionDetail::addMedia($collectionId, $mediaId);
    }

    static public function moveCollection ($collectionId, $targetCollectionIds) {
      $collection = self::findExtended($collectionId);
      $mediumIds  = array();
      foreach ($collection->getMedia() as $medium) {
        array_push($mediumIds, $medium->id);
      }
      return self::moveMedia($collectionId, $mediumIds, $targetCollectionIds);
    }

    static public function moveMedia ($sourceCollectionId, $mediumIds, $targetCollectionIds) {
      $collection   = Collection::find($sourceCollectionId);
      $shouldDelete = true;
      if ( !$collection->isOriginal() || self::isPseudo($collection->id) ) {   // Don't delete if it's copied/categoried folder
        $shouldDelete = false;
      }

      $toDeleteIds = [];
      if ($shouldDelete) {
        // Delete media only when all targetCollections don't have it
        $toDeleteIds = array_filter($mediumIds, function ($id) use ($targetCollectionIds) {
          $test = array_map(function ($targetId) use ($id) {
            $targetMediaIds = self::findExtended($targetId)->getMedia()->map(function ($m) { return $m->id; })->toArray();
            return in_array(intval($id), $targetMediaIds);
          }, $targetCollectionIds);
          return !in_array(true, $test);
        });
      }

      // copy only different source/target (prevent duplicate)
      self::copyMedia($mediumIds, array_filter($targetCollectionIds, function ($id) use ($sourceCollectionId) {
        return $id != $sourceCollectionId;
      }));

      if ( !$shouldDelete ) {
        return false;
      }

      return self::deleteMedia($sourceCollectionId, $toDeleteIds);
    }

    static public function addToBundle ($id) {
      $collection = self::findExtended($id);
      $mediumIds  = array();
      foreach ($collection->getMedia() as $medium) {
          $mediaData = CollectionDetail::findMediaOrderByCollectionId($id, $medium->id);
          $mediaSortNumber = $mediaData->sort_order;
          if (isset($mediumIds[$mediaSortNumber])) {
            $mediaSortNumber = count($mediumIds);
          }
          $mediumIds[$mediaSortNumber]  = $medium->id;
          //array_push($mediumIds, $medium->id);
      }
      return BundleCart::bulkAddMedia($mediumIds);
    }

    static public function removeFromBundle ($id) {
      $mediumIds = self::findExtended($id)->getMedia()->map(function ($item) {
        return $item->id;
      });
      BundleCart::bulkRemoveMedia($mediumIds);
    }

    static public function isInBundle ($id) {
      $bundled = BundleCart::getBundleCartList(Auth::user()->id);
      $bundledMediaIds = array_map(function ($item) {
        return $item->id;
      }, $bundled === false ? [] : $bundled);
      $media     = self::findExtended($id)->getMedia();
      $filtereds = $media->filter(function ($item) use ($bundledMediaIds) {
        return in_array($item->id, $bundledMediaIds);
      });
      return count($media) > 0 && count($filtereds) === count($media);
    }

    static public function pin ($id, $pin = true) {
      $collection = self::findExtended($id);
      $collection->is_pin = $pin;
      $collection->save();
      return $collection;
    }
	
    public static function isFolderAvailableForCopy($folder_id)
    {
      return Collection::where('original_id', $folder_id)
                        ->where('user_id', Auth::user()->id)
                        ->count();
    }

  static public function createFromCategory ($categoryId, $userId = null) {
    $useUserId  = is_null($userId) ? Auth::user()->id : $userId;
    $category   = Category::find($categoryId);
    $collection = new Collection([
      'user_id'     => $useUserId,
      'name'        => $category->name,
      'description' => $category->description,
      'category_id' => $category->id
    ]);
    $collection->save();
    foreach ($category->media as $medium) {
      $collection->media()->attach($medium->id, ['sort_order' => $medium->pivot->sort_order, 'user_id' => $useUserId]);
    }
    return $collection;
  }

  static protected function boot () {
    parent::boot();
    static::deleting(function ($collection) {
      foreach ($collection->children as $child) {
        self::destroy($child->id);
      }
      CollectionDetail::deleteCollectionOf($collection->id);
    });
  }
}
