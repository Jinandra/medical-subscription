<?php
  namespace App\Models;

  use Illuminate\Database\Eloquent\Model;    
  use Zizaco\Entrust\Traits\EntrustUserTrait;
  use Illuminate\Auth\Authenticatable;
  use Illuminate\Auth\Passwords\CanResetPassword;
  use Illuminate\Foundation\Auth\Access\Authorizable;
  use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
  use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
  use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
  use Vuer\Token\Traits\Tokenable;
  use DB;

  use App\Models\Collection;
  use App\Models\Ucode;
  use App\Models\DetailUcode;
  use App\Models\UcodeHistory;
  use App\Models\BundleCart;

  use App\Library\S3;

  use File;


    class User extends Model implements AuthenticatableContract,
                                        AuthorizableContract,
                                        CanResetPasswordContract
    {
        use Authenticatable, Authorizable, CanResetPassword,
          EntrustUserTrait {
            EntrustUserTrait::can insteadof Authorizable;
          }

        use Tokenable;


        protected $table = 'users';
        
        const FOLDER_PREFIX = 'user_';
        const MEDIA_TOTAL_SIZE = 104857600;
        const ONE_MB = 1048576;

        const STATUS_ACTIVE  = 'active';
        const STATUS_BLOCKED = 'blocked';
        const STATUS_PENDING_ACTIVATION   = 'pending';
        const STATUS_PENDING_VERIFICATION = 'need_verification';
        
        public function media() {
            return $this->hasMany('App\Models\Media', 'user_id', 'id');
        }
        
        public function mediaReports() {
            return $this->hasMany('App\Models\MediaReport', 'user_id', 'id');
        }
        
        public function mediaBans() {
            return $this->hasMany('App\Models\MediaBan', 'user_id', 'id');
        }
        
        public function emailHistories () {
            return $this->hasMany('App\Models\EmailHistory', 'user_id', 'id');
        }

        public function textHistories () {
            return $this->hasMany('App\Models\TextHistory', 'user_id', 'id');
        }
        
        public function collection() {  // TODO: remove me, compat for old code
            return $this->hasMany('App\Models\Collection', 'user_id', 'id');
        }

        public function collections () {
            return $this->hasMany('App\Models\Collection', 'user_id', 'id');
        }

        public function bundles () {
            return $this->hasMany('App\Models\BundleCart', 'user_id', 'id');
        }

        public function medicalInterests () {
          return $this->belongsToMany('App\Models\Category');
        }

        public function bookmarkedMedia () {
          return $this->hasMany('App\Models\Favorite', 'user_id', 'id')->with('media');
        }

        public function likedMedia () {
          return $this->hasMany('App\Models\LikeDislike', 'user_id', 'id')->with('media')->where('like', 1);
        }
		
        public static function getSingleUser($user_id)
        {
            $result = DB::select("select * from users where id='$user_id'");
            return $result[0];
        }

  public function ucodes () {
    return $this->hasMany('App\Models\Ucode');
  }

  /**
   * Returns user's ucodes with statistic data (last accessed, count accessed, total media, total media in bundle)
   * @param string $ucode ucode query
   */
  public function statsUcodes ($ucode, $paginate = 10) {
    $tbnUcode         = (new Ucode)->getTable();
    $tbnDetailUcode   = (new DetailUcode)->getTable();
    $tbnUcodeHistory  = (new UcodeHistory)->getTable();
    $tbnBundleCart    = (new BundleCart)->getTable();
    return DB::table($tbnUcode.' AS UCO')
      ->select('*')
      // count of media in a ucode
      ->leftJoin(DB::raw('( SELECT ucode_id, COUNT(*) AS countOnUcode FROM '.$tbnDetailUcode.' GROUP BY ucode_id ) as DC'), 'UCO.id', '=', 'DC.ucode_id')
      // count of media in a ucode that currently in bundle cart
      ->leftJoin(DB::raw('( SELECT ucode_id, COUNT(*) AS countOnBundle FROM '.$tbnDetailUcode.' WHERE id_media IN (SELECT media_id FROM '.$tbnBundleCart.' WHERE user_id = '.$this->id.' ) GROUP BY ucode_id ) AS BU'), 'UCO.id', '=', 'BU.ucode_id')
      // is this ucode in bundle cart?
      /* ->leftJoin(DB::raw('( SELECT ucode_id, COUNT(*) AS id_media_bundle_cart, '.$tbnDetailUcode.'.ucode AS ucodeBundle FROM '.$tbnUcode.', '.$tbnDetailUcode.', '.$tbnBundleCart.' WHERE '.$tbnUcode.'.ucode = '.$tbnDetailUcode.'.ucode AND '.$tbnBundleCart.'.media_id = '.$tbnDetailUcode.'.id_media AND '.$tbnUcode.'.email = "'.$useremail.'" GROUP BY '.$tbnDetailUcode.'.ucode ORDER BY '.$tbnDetailUcode.'.ucode ASC ) as BC'), 'UC.ucode', '=', 'BC.ucodeBundle') */
      // last accessed by guest or another user
      ->leftJoin(DB::raw('(SELECT ucode_id, MAX(created_at) AS uCodeHistoryCreatedAt FROM '.$tbnUcodeHistory.' WHERE user_id IS NULL OR user_id != '.$this->id.' GROUP by ucode_id ORDER BY created_at ASC ) AS UH'), 'UCO.id', '=', 'UH.ucode_id')
      // accessed count by guest or another user
      ->leftJoin(DB::raw('( SELECT ucode_id, COUNT(*) AS countUcodeHistory FROM '.$tbnUcodeHistory.' WHERE user_id IS NULL OR user_id != '.$this->id.' GROUP BY ucode_id ) AS CU'), 'UCO.id', '=', 'CU.ucode_id')
      ->where(function ($query) use($ucode) {
        $query->where('UCO.ucode', 'LIKE', '%'.$ucode.'%');
      })
      ->where('UCO.user_id', $this->id)
      ->orderBy('UCO.created_at', 'desc')
      ->paginate($paginate);
  }

  public function savedMedia () {
    return Collection::with('media')
      ->where('user_id', $this->id)
      ->whereNotNUll('original_id')
      ->get()
      ->reduce(function ($all, $collection) {
        return $all->merge($collection->media);
      }, new \Illuminate\Database\Eloquent\Collection())
      ;
  }

  public function savedFolders () {
    return Collection
      ::where('user_id', $this->id)
      ->whereNotNull('original_id')
      ->with(['media' => function ($q) {
        $q->orderBy('collection_details.sort_order', 'ASC');
      }])
      ->orderBy('name', 'ASC')
      ->get();
  }

  public function createdFolders () {
    return Collection
      ::where('user_id', $this->id)
      ->whereNull('original_id')
      ->whereNull('category_id')
      ->with(['media' => function ($q) {
        $q->orderBy('collection_details.sort_order', 'ASC');
      }])
      ->orderBy('name', 'ASC')
      ->get();
  }

  public function categoriedFolders () {  // populate from category (medicalInterests)
    return Collection
      ::where('user_id', $this->id)
      ->whereNotNull('category_id')
      ->with(['media' => function ($q) {
        $q->orderBy('collection_details.sort_order', 'ASC');
      }])
      ->orderBy('name', 'ASC')
      ->get();
  }


  public function syncMedicalInterestCollections () {
    foreach ($this->medicalInterests as $category) {
      $collection = Collection::where('user_id', $this->id)->where('category_id', $category->id)->first();
      if (is_null($collection)) {
        Collection::createFromCategory($category->id, $this->id);
      }
    }
    // Delete no longer interests
    $categoryIds = $this->medicalInterests->map(function ($category) { return $category->id; })->toArray();
    Collection::where('user_id', $this->id)->whereNotIn('category_id', $categoryIds)->delete();
  }

  public function activated () {
    if (!$this->hasRole(Role::USER)) {
      $this->attachRole(Role::regularUser()->first()->id);
    }
    $this->user_status = 'active';
    $this->verified_at = \Carbon\Carbon::now()->toDateTimeString();
    return $this->save();
  }

  public function declined ($message) {
    $this->user_status = 'declined';
    $this->decline_message = $message;
    $this->declined_at = \Carbon\Carbon::now()->toDateTimeString();
    return $this->save();
  }

  public function fullname () {
    return "{$this->first_name} {$this->last_name}";
  }

  public function initialName () {
    $l = substr($this->last_name, 0, 1);
    return substr($this->first_name, 0, 1) . ($l === FALSE ? '' : $l);
  }

  public function fullnameWithScreenName () {
    return $this->fullname() . " ({$this->screen_name})";
  }

  /**
   * Get s3 path for the user (user_id)
   */
  static public function resourcesPath ($userId) {
    return self::FOLDER_PREFIX.$userId;
  }
  public function getResourcesPath () {
    return self::resourcesPath($this->id);
  }
  /**
   * Get s3 url for the user (https://s3.amazonaws.com/(resources_name)/user_id)
   */
  static public function resourcesUrl ($userId) {
    return config("app.resources_url")."/".self::FOLDER_PREFIX.$userId;
  }
  public function getResourcesUrl () {
    return self::resourcesUrl($this->id);
  }

  public function getMediaUsedSize () {
    $result = S3::listObjects($this->getResourcesPath());
    $size   = 0;
    if (count($result["Contents"]) > 0) {
      foreach ($result["Contents"] as $file) {
        if ( preg_match('/'.Media::THUMBNAIL_NAME.'$/', $file['Key']) === 0 &&
             preg_match('/'.Media::WEBSITE_THUMBNAIL_NAME.'$/', $file['Key']) === 0 ) { // ignore thumbnails
          $size += $file["Size"];
        }
      }
    }
    return $size;
  }
  
  public function getMediaUsedSizePersentage() {
    $size = $this->getMediaUsedSize();
    return round(($size / self::MEDIA_TOTAL_SIZE) * 100, 2);
  }
  
  public function getMediaAllowedSize() {
    $size = $this->getMediaUsedSize();
    return self::MEDIA_TOTAL_SIZE - $size;
  }

  public function isAdministrator () {
    return $this->hasRole(Role::ADMINISTRATOR);
  }
  public function isMasterAdministrator () {
    return $this->hasRole(Role::MASTER_ADMINISTRATOR);
  }
  public function isRegularUser () {
    return $this->hasRole(Role::USER);
  }
  public function isPaidUser () {
    return $this->hasRole(Role::PAID_USER);
  }

  public function isStatusActive () {
    return $this->user_status === self::STATUS_ACTIVE;
  }
  public function isStatusBlocked () {
    return $this->user_status === self::STATUS_BLOCKED;
  }
  public function isStatusPendingActivation () {
    return $this->user_status === self::STATUS_PENDING_ACTIVATION;
  }
  public function isStatusPendingVerification () {
    return $this->user_status === self::STATUS_PENDING_VERIFICATION;
  }

  public function blocks () {
    $this->user_status = self::STATUS_BLOCKED;
    $this->save();
  }
  public function unblocks () {
    $this->user_status = self::STATUS_ACTIVE;
    $this->save();
  }

  public function getDisplayRoleName () {
    if ($this->isMasterAdministrator()) {
      return Role::masterAdministrator()->first()->display_name;
    } else if ($this->isAdministrator()) {
      return Role::administrator()->first()->display_name;
    } else if ($this->isPaidUser()) {
      return Role::paidUser()->first()->display_name;
    } else if ($this->isRegularUser()) {
      return Role::regularUser()->first()->display_name;
    }
    return '';
  }

  public function roleNames () {
    return $this->roles()->get()->map(function ($role) { return $role->display_name; })->toArray();
  }

  static protected function boot () {
    parent::boot();
    static::deleting (function ($user) {
      $user->medicalInterests()->detach();
      Media::where('user_id', $user->id)->delete();
      BundleCart::where('user_id', $user->id)->delete();
      Collection::where('user_id', $user->id)->delete();
      MediaBan::where('user_id', $user->id)->delete();

      Favorite::where('user_id', $user->id)->delete();
      Ucode::where('user_id', $user->id)->delete();
      LikeDislike::where('user_id', $user->id)->delete();
    });
  }


  public function emptyBundleCart () {
    return BundleCart::where('user_id', $this->id)->delete();
  }
}
