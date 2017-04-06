<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB,
    App,
    Auth,
    Html;
use App\Library\Url2Png,
    App\Library\S3,
    App\Library\VideoSite;

class Media extends Model {
    protected $table = 'media';

    const FOLDER_PREFIX  = 'media_';
    const THUMBNAIL_NAME = 'thumbnail.png';
    const WEBSITE_THUMBNAIL_NAME = 'website_thumbnail.png';

    const TYPE_WEBSITE = 'website';
    const TYPE_VIDEO = 'video';
    const TYPE_IMAGE = 'image';
    const TYPE_DOCUMENT = 'text';

    const EXT_DOC = 'doc';
    const EXT_PPT = 'ppt';
    const EXT_XLS = 'xls';
    const EXT_PDF = 'pdf';
    
    const STATUS_PUBLIC = 0;
    const STATUS_PRIVATE = 1;
    
    const SHOW_TYPE_ALL = '';
    const SHOW_TYPE_ONLINE = 'online';
    const SHOW_TYPE_UPLOADED = 'uploaded';
    const SHOW_TYPE_CREATED = 'created';
    
    const CREATE_TYPE_ONLINE = 'online';
    const CREATE_TYPE_UPLOAD = 'upload';
    const CREATE_TYPE_CREATE = 'create';
    
    const SOURCE_NOT_APPLICABLE = 1;
    const SOURCE_HEALTH_EXPERT = 2;
    const SOURCE_HEALTH_PROFESSIONAL = 3;
    const SOURCE_HEALTH_ORGANISATION = 4;
    
    const EXPERTISE_LEVEL_NOT_APPLICABLE = 1;
    const EXPERTISE_LEVEL_INTERMEDIATE = 2;
    const EXPERTISE_LEVEL_ADVANCED = 3;
    const EXPERTISE_LEVEL_EXPERT = 4;
    
    const AUDIENCE_GENERAL_PUBLIC = 1;
    const AUDIENCE_HEALTH_PROFESSIONALS = 2;
    const AUDIENCE_COLLEGE_EDUCATED = 3;
    const AUDIENCE_CHILDREN = 4;
    
    const CAPTION_AVAILABLE = 1;
    const CAPTION_UNAVAILABLE = 0;
    
    const UPLOAD_PATH = 'uploads/media/';
    
    static public function getAllowedExtensions() {
      return [
        'jpeg',
        'jpg',
        'png',
        'gif',
        'doc',
        'docx',
//        'ppt',
//        'pptx',
        'pdf',
//        'xls',
//        'xlsx'
      ];
    }
    
    static public function getMSExtensions() {
      return [
        'doc',
        'docx',
//        'ppt',
//        'pptx',
//        'xls',
//        'xlsx'
      ];
    }
    
    static public function getAllShowTypes() {
      return [
        self::SHOW_TYPE_ALL => 'All',
        self::SHOW_TYPE_ONLINE => 'Online',
        self::SHOW_TYPE_UPLOADED => 'Uploaded',
//        self::SHOW_TYPE_CREATED => 'Created'
      ];
    }
    
    static public function getAllCreateTypes() {
      return [
        self::CREATE_TYPE_ONLINE => 'Online',
        self::CREATE_TYPE_UPLOAD => 'Upload',
//        self::CREATE_TYPE_CREATE => 'Create'
      ];
    }
    
    static public function getAllOnlineTypes() {
      return [
        self::TYPE_DOCUMENT => 'Document link: pdf, doc, docx, google drive doc, google drive pdf',
        self::TYPE_VIDEO => 'Video link: Youtube, Google Drive',
        self::TYPE_IMAGE => 'Image link: png, jpg, gif',
        self::TYPE_WEBSITE => 'Website link: no restriction'
      ];
    }
    
    static public function getAllUploadTypes() {
      return [
        self::TYPE_DOCUMENT => 'Document: pdf, docx',
        self::TYPE_IMAGE => 'Image: png, jpg, gif'
      ];
    }
    
    static public function getShowTypeIconClass($type) {
      switch ($type) {
        case self::CREATE_TYPE_ONLINE:
          return 'fa-globe';
        case self::CREATE_TYPE_UPLOAD:
          return 'fa-cloud-upload';
        case self::CREATE_TYPE_CREATE:
          return 'fa-plus-circle';
        }
        return '';
    }
    
    static public function getAllSources() {
      return [
        self::SOURCE_NOT_APPLICABLE => 'Not Applicable',
        self::SOURCE_HEALTH_EXPERT => 'Health Expert',
        self::SOURCE_HEALTH_PROFESSIONAL => 'Health Professional',
        self::SOURCE_HEALTH_ORGANISATION => 'Health Organisation'
      ];
    }
    
    static public function getAllExpertiseLevels() {
      return [
        self::EXPERTISE_LEVEL_NOT_APPLICABLE => 'Not Applicable',
        self::EXPERTISE_LEVEL_INTERMEDIATE => 'Intermidiate',
        self::EXPERTISE_LEVEL_ADVANCED => 'Advanced',
        self::EXPERTISE_LEVEL_EXPERT => 'Expert'
      ];
    }
    
    static public function getAllAudiences() {
      return [
        self::AUDIENCE_GENERAL_PUBLIC => 'General Public',
        self::AUDIENCE_HEALTH_PROFESSIONALS => 'Health Professionals',
        self::AUDIENCE_COLLEGE_EDUCATED => 'College Educated',
        self::AUDIENCE_CHILDREN => 'Children'
      ];
    }
    
    public function user() {
      return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
    
    public function categories() {
      return $this->belongsToMany('App\Models\Category', 'media_category', 'media_id', 'category_id');
    }
    
    public function collections() {
      return $this->belongsToMany('App\Models\Collection', 'collection_details', 'media_id', 'collection_id');
    }
    
    public function userCollections ($userId) {
      return CollectionDetail::where('media_id', $this->id)->where('user_id', $userId)->get();
    }
    
    public function reports() {
      return $this->hasMany('App\Models\MediaReport', 'media_id', 'id');
    }


    // === File type detection

    static public function IsTypeDocument ($media) {
      return $media->type === self::TYPE_DOCUMENT;
    }
    static public function IsTypeWebsite ($media) {
      return $media->type === self::TYPE_WEBSITE;
    }
    static public function IsTypeVideo ($media) {
      return $media->type === self::TYPE_VIDEO;
    }
    static public function IsTypeImage ($media) {
      return $media->type === self::TYPE_IMAGE;
    }

    static public function IsFileExt ($media, $ext) {
      return self::isExt($media->file_name, $ext);
    }
    static public function IsLinkExt ($media, $ext) {
      return self::isExt($media->web_link, $ext);
    }
    static public function IsFileMS ($media) {
      $arr = explode('.', $media->file_name);
      $ext = array_pop($arr);
      return in_array($ext, self::getMSExtensions());
    }
    static private function isExt ($filename, $ext) {
      switch ($ext) {
      case self::EXT_DOC:
        return substr($filename, -4) === '.doc' || substr($filename, -5) === '.docx';
      case self::EXT_XLS:
        return substr($filename, -4) === '.xls' || substr($filename, -5) === '.xlsx';
      case self::EXT_PPT:
        return substr($filename, -4) === '.ppt' || substr($filename, -5) === '.pptx';
      case self::EXT_PDF:
        return substr($filename, 4) === '.pdf';
      default:
        return false;
      }
    }

    static public function IsUploaded ($media) {
      return !is_null($media->file_name) && !empty($media->file_name);
    }
    static public function IsOnline ($media) {
      return !is_null($media->web_link) && !empty($media->web_link);
    }



    // ==== PATH & URL for thumbnail and file

    /**
     * Return s3 path for the media (/user_id/media_id)
     */
    static public function resourcesPath ($media) {
      return User::resourcesPath($media->user_id)."/".self::FOLDER_PREFIX.$media->id;
    }
    public function getResourcesPath () {
      return self::resourcesPath($this);
    }
    /**
     * Return s3 url for the media (https://s3.amazonaws.com/(resources_name)/user_id/media_id
     */
    static public function resourcesUrl ($media) {
      return User::resourcesUrl($media->user_id)."/".self::FOLDER_PREFIX.$media->id;
    }
    public function getResourcesUrl () {
      return self::resourceUrl($this);
    }
    /**
     * Return Media's thumbnail url (http://s3.amazonaws.com/(resources_name)/user_id/media_id/thumbnails.png
     */
    static public function thumbnailUrl ($media) {
      return self::resourcesUrl($media)."/".self::THUMBNAIL_NAME;
    }
    public function getThumbnailUrl () {
      return self::thumbnailUrl($this);
    }
    /**
     * Return Media's thumbnail path for upload to s3 (user_id/media_id/thumbnails.png)
     */
    static public function thumbnailPath ($media) {
      return self::resourcesPath($media)."/".self::THUMBNAIL_NAME;
    }
    public function getThumbnailPath () {
      return self::thumbnailPath($this);
    }
    /**
     * Return Media's type website thumbnail url (http://s3.amazonaws.com/(resources_name)/user_id/media_id/website_thumbnails.png
     */
    static public function websiteThumbnailUrl ($media) {
      return self::resourcesUrl($media)."/".self::WEBSITE_THUMBNAIL_NAME;
    }
    public function getWebsiteThumbnailUrl () {
      return self::websiteThumbnailUrl($this);
    }
    /**
     * Return Media's type website thumbnail path for upload to s3 (user_id/media_id/website_thumbnails.png)
     */
    static public function websiteThumbnailPath ($media) {
      return self::resourcesPath($media)."/".self::WEBSITE_THUMBNAIL_NAME;
    }
    public function getWebsiteThumbnailPath () {
      return self::websiteThumbnailPath($this);
    }
    /**
     * Return Media's file url (https://s3.amazonaws.com/(resources_name)/user_id/media_id/filename
     */
    static public function fileUrl ($media) {
      return self::IsUploaded($media) ? self::resourcesUrl($media)."/".$media->file_name : $media->web_link;
    }
    public function getFileUrl () {
      return self::fileUrl($this);
    }
    /**
     * Return Media's path file for upload to s3 (user_id/media_id/filename)
     */
    static public function filePath ($media) {
      return self::resourcesPath($media)."/".$media->file_name;
    }
    public function getFilePath () {
      return self::filePath($this);
    }

    static public function sendToCollection ($mediaId, $collectionId) {
      $count = DB::table('collection_details')
        ->where('media_id', $mediaId)
        ->where('collection_id', $collectionId)
        ->where('user_id', Auth::user()->id)
        ->count();
      if ($count > 0) {
        return false;
      }
      DB::table('collection_details')
        ->insert([
          'media_id' => $mediaId,
          'collection_id' => $collectionId,
          'user_id' => Auth::user()->id
        ]);
      return true;
    }

    // convert eloquent to object with filled fields
    static public function findSingle ($id) {
      $media = self::qAll()->where('M.id', $id)->get();
      return self::fillMediaFields($media)[0];
    }



    // === Query for homepage

    // Query Popular media that popular in 1 day
    public function popular1Day($favorite = "") {
      $yesterday = Carbon::now()->yesterday();
      return self::fillMediaFields($this->_queryTimePopularity($yesterday), $favorite);
    }

    // Query Popular media that popular in 1 week
    public function popular1Week($favorite = "") {
      $oneWeekBefore = Carbon::now()->subWeek();
      return self::fillMediaFields($this->_queryTimePopularity($oneWeekBefore), $favorite);
    }

    // Query Popular media that popular in 1 month
    public function popular1Month($favorite = "") {
      $oneMonthBefore = Carbon::now()->subMonth();
      return self::fillMediaFields($this->_queryTimePopularity($oneMonthBefore), $favorite);
    }
    static public function newlyAdded ($favorite = "") {
      $medias = self::qAll(true)
        ->orderBy('M.created_at', 'DESC')
        ->limit(20)
        ->get();
      return self::fillMediaFields($medias, $favorite);
    }
    private function _queryTimePopularity ($startTime) {
      $today = Carbon::now()->toDateTimeString();
      return DB::table((new History)->getTable().' AS history')
        ->select('M.*', 'history.id_media AS history_id_media', DB::raw('COUNT(history.id_media) AS history_count'))
        ->join(DB::raw('( '.self::qAll(true)->toSql().' ) as M'), 'M.id', '=', 'history.id_media')
        ->whereBetween('history.created_at', array($startTime, $today))
        ->groupBy('history_id_media')
        ->orderBy('history_count', 'DESC')
        ->limit(20)
        ->get();
    }


    // Return like/dislike in percentage
    static private function _calculatePopularityPercentage ($like, $dislike) {
      $likePercent    = 0;
      $dislikePercent = 0;
      if ($like + $dislike > 0) {
        $likePercent    = ceil(($like / ($like + $dislike)) * 100);
        $dislikePercent = ceil(($dislike / ($like + $dislike)) * 100);
      }
      return array(
        'likePercent'    => $likePercent,
        'dislikePercent' => $dislikePercent
      );
    }
    // Return count like/dislike and its percentage
    static private function _calculatePopularity ($colsLikeDislike) {
      $like     = 0;
      $dislike  = 0;
      foreach ($colsLikeDislike as $row) {
        if ($row->like == '1') {
          $like++;
        } else if ($row->dislike == '1') {
          $dislike++;
        }
      }
      return array_merge(
        array('count_like' => $like, 'count_dislike' => $dislike),
        self::_calculatePopularityPercentage($like, $dislike)
      );
    }
    /**
     * Get media popularity fields (like, dislike, likePercent, dislikePercent)
     * @return Array media popularity fields
     */
    public function popularity () {
      $cols = DB::table('like_dislike')->where('id_media', $this->id)->get();
      return self::_calculatePopularity($cols);
    }
    /**
     * Get media popularity (@see popularity)
     * @param int $id media id
     * @return Array media popularity fields
     */
    static public function getPopularity ($id) {  // static version
      $cols = DB::table('like_dislike')->where('id_media', $id)->get();
      return self::_calculatePopularity($cols);
    }
    /**
     * Fill array of of media with necessary fields
     * @param Array $media array of media object (from DB)
     * @param Array $favorite user's favorite media
     * @return Array array of media filled
     */
    static public function fillMediaFields ($media, $favorite = "") {
        if ($favorite == "" && Auth::check()) {
          $favorite = DB::table('favorite')->where('user_id', Auth::user()->id)->get();
        }

        for ($i = 0; $i < count($media); $i++) {
          if (isset($media[$i]->id_media))
              $media[$i]->id = $media[$i]->id_media;
          else
              $media[$i]->id_media = $media[$i]->id;

          if ( !property_exists($media[$i], 'email') || !property_exists($media[$i], 'screen_name') ) {
            $mediaUser = User::whereId($media[$i]->user_id)->first();
            if ( !property_exists($media[$i], 'email') ) {
              $media[$i]->email = $mediaUser->email;
            }
            if ( !property_exists($media[$i], 'screen_name') ) {
              $media[$i]->screen_name = $mediaUser->screen_name;
            }
          }

          // Whether current user save the media in bundle or not
          if ( !property_exists($media[$i], 'id_media_bundle_cart') && Auth::check() ) {
            $media[$i]->id_media_bundle_cart = DB::table('bundle_cart')
              ->where('media_id', $media[$i]->id_media)
              ->where('user_id', Auth::user()->id)
              ->value('media_id');
          }

          // Count Like / Dislike
          if ( !property_exists($media[$i], 'count_like') || !property_exists($media[$i], 'count_dislike') ) {
            $popularity = self::getPopularity($media[$i]->id_media);
            $media[$i]->count_like     = $popularity['count_like'];
            $media[$i]->count_dislike  = $popularity['count_dislike'];
            $media[$i]->likePercent    = $popularity['likePercent'];
            $media[$i]->dislikePercent = $popularity['dislikePercent'];
          } else {
            if ( is_null($media[$i]->count_like) )    { $media[$i]->count_like = 0; }
            if ( is_null($media[$i]->count_dislike) ) { $media[$i]->count_dislike = 0; }
          }
          if ( !property_exists($media[$i], 'likePercent') || !property_exists($media[$i], 'dislikePercent') ) {
            $percentPopularity = self::_calculatePopularityPercentage($media[$i]->count_like, $media[$i]->count_dislike);
            $media[$i]->likePercent    = $percentPopularity['likePercent'];
            $media[$i]->dislikePercent = $percentPopularity['dislikePercent'];
          } else {
            if ( is_null($media[$i]->likePercent) )    { $media[$i]->likePercent = 0; }
            if ( is_null($media[$i]->dislikePercent) ) { $media[$i]->dislikePercent = 0; }
          }

          // Count times collected
          if ( !property_exists($media[$i], 'count_cd') ) {
            $media[$i]->count_cd = CollectionDetail
              ::where('media_id', $media[$i]->id_media)
              ->where('user_id', '!=', $media[$i]->user_id)
              ->distinct()
              ->count('user_id') + 1;
          }

          // Additional attributes
          if ( self::IsTypeVideo($media[$i]) ) {
            if ( VideoSite::isYoutube($media[$i]->web_link) ) {
              $media[$i]->youtubeEmbed = VideoSite::getYoutubeEmbed($media[$i]->web_link);
            } else if ( VideoSite::isGoogleDrive($media[$i]->web_link) ) {
              $media[$i]->driveEmbed = VideoSite::getDriveEmbed($media[$i]->web_link);
            }
          }

          // Whether current user bookmark the media or not
          if ( !property_exists($media[$i], 'fav') ) {
            $fav = false;
            if ($favorite != "") {
                for ($j = 0; $j < count($favorite); $j++) {
                    if ($favorite[$j]->id_media == $media[$i]->id)
                        $fav = true;
                }
            }
            $media[$i]->fav = $fav;
          } else {
            $media[$i]->fav = is_null($media[$i]->fav) ? false : true;
          }

          $media[$i]->created_at = Carbon::parse($media[$i]->created_at)->format('m/d/Y');

          //Check whether user like or dislike this media
          if ( !property_exists($media[$i], 'like') || !property_exists($media[$i], 'dislike') ) {
            $media[$i]->like    = false;
            $media[$i]->dislike = false;
            if (Auth::check() ) {
              $likedislike = LikeDislike::where('id_media', $media[$i]->id)->first();
              if (!empty($likedislike->exists)) {
                  $media[$i]->like = ($likedislike['like'] == 1) ? true : false;
                  $media[$i]->dislike = ($likedislike['dislike'] == 1) ? true : false;
              }
            }
          } else {
            if ( is_null($media[$i]->like) || $media[$i]->like === 0 )       { $media[$i]->like = false; }
            if ( is_null($media[$i]->dislike) || $media[$i]->dislike === 0 ) { $media[$i]->dislike = false; }
            if ( $media[$i]->like === 1 )    { $media[$i]->like = 1; }
            if ( $media[$i]->dislike === 1 ) { $media[$i]->dislike = 1; }
          }

          // Thumbnail
          $media[$i]->thumbnail_url = Media::thumbnailUrl($media[$i]);

          // Get the last accessed not by the creator
          if ( !property_exists($media[$i], 'lastAccessed') ) {
            $media[$i]->lastAccessed =
              DB::table('history')
              ->where('user_id', '!=', $media[$i]->user_id)
              ->where('id_media', $media[$i]->id)
              ->orderBy('created_at', 'DESC')
              ->limit(1)
              ->value('created_at');
          }
          if ( is_null($media[$i]->lastAccessed) || $media[$i]->lastAccessed === '-' ) {
            $media[$i]->lastAccessed = '-';
          } else {
            $media[$i]->lastAccessed = Carbon::parse($media[$i]->lastAccessed)->format('m/d/Y');
          }

          // Get view count from history
          if ( !property_exists($media[$i], 'view_count') ) {
            $media[$i]->view_count = History::where('id_media', $media[$i]->id_media)->count();
          }

          // Resolve web link && website thumbnail
          if ( self::IsOnline($media[$i]) ) {
            $media[$i]->resolved_web_link = $media[$i]->web_link;
          } else if ( self::IsUploaded($media[$i]) ) {
            $media[$i]->resolved_web_link = self::fileUrl($media[$i]);
          }
          if ( self::IsTypeWebsite($media[$i]) ) {
            $media[$i]->website_thumbnail_url = self::websiteThumbnailUrl($media[$i]);
          }
        }
        return $media;
    }



  /**
   * Return source url for generate the screenshot (external or aws)
   * @return {string} url for taking screenshot
   */
  static public function sourceUrlForShot ($media) {
    $url = $media->web_link;
    if ( self::IsOnline($media) ) {
      if ( self::IsTypeDocument($media) && self::IsLinkExt($media, self::EXT_DOC) ) {
        $url = url('/media-document/'.$media->id);
      }
    } else if ( self::IsUploaded($media) ) {
      $url = self::fileUrl($media);
      if ( self::IsFileMS($media) ) {
        $url = url('/media-document/'.$media->id);
      }
    }
    return $url;
  }
  public function getSourceUrlForShot () {
    return self::sourceUrlForShot($this);
  }

  private function urlencode ($url) {
    $parts = parse_url($url);
    return $parts['scheme'].'://'.$parts['host'].'/'.
      urlencode(
        $parts['path'].
        ( !isset($parts['query']) || empty($parts['query']) ? '' : '?'.$parts['query']).
        ( !isset($parts['fragment']) || empty($parts['fragment']) ? '' : '#'.$parts['fragment'])
      );
  }
  /**
   * Generate thumbnail and return tmp path of the screenshot
   * @return {string} path of thumbnail screenshot
   */
  public function generateThumbnail () {
    $this->generateWebsiteThumbnail();
    $path = null;
    try {
      $url2png = new Url2Png();
      $src = '';
      if ( self::IsTypeImage($this) ) {
        // Download the image and make thumbnail
        $path = tempnam("tmp", "thumbnail");
        file_put_contents($path, file_get_contents($this->urlencode($this->getFileUrl())));
        $image = new \Imagick($path);
        $image->thumbnailImage(300, 0);
        $image->writeImage($path);
        S3::putObject($this->getThumbnailPath(), $path);
        unlink($path);
        return $path;
      } else {
        if ( VideoSite::isYoutube($this->web_link) ) {
          $src = VideoSite::youtubeThumbnailUrl($this->web_link);
        } else {
          $src = $url2png->url2png_v6($this->getSourceUrlForShot(), [
            'force' => 'always',              # [false,always,timestamp] Default: false
            'fullpage' => 'false',            # [true,false] Default: false
            'thumbnail_max_width' => '300',   # scaled image width in pixels; Default no-scaling.
            'viewport' => "1366x768"          # Max 5000x5000; Default 1280x1024
          ]);
        }
        $path = tempnam("tmp", "thumbnail");
        file_put_contents($path, file_get_contents($this->urlencode($src)));
        S3::putObject($this->getThumbnailPath(), $path);
        unlink($path);
      }
    } catch (\Exception $e) {
      S3::putObject($this->getThumbnailPath(), "assets/images/nopreview.png");
      echo $e;
      $path = null;
    }
    return $path;
  }
  /**
   * Generate website thumbnail (large resolution) and return tmp path of the screenshot
   * @return {string} path of website thumbnail screenshot
   */
  private function generateWebsiteThumbnail () {
    if ( !self::IsTypeWebsite($this) ) { return null; }
    $path = null;
    try {
      $url2png = new Url2Png();
      $src = '';
      $src = $url2png->url2png_v6($this->web_link, [
        'force' => 'false',               # [false,always,timestamp] Default: false
        'fullpage' => 'false',            # [true,false] Default: false
        'thumbnail_max_width' => '1024',  # scaled image width in pixels; Default no-scaling.
        'viewport' => "1366x768"          # Max 5000x5000; Default 1280x1024
      ]);
      $path = tempnam("tmp", "websitethumbnail");
      file_put_contents($path, file_get_contents($src));
      S3::putObject($this->getWebsiteThumbnailPath(), $path);
      unlink($path);
    } catch (\Exception $e) {
      S3::putObject($this->getWebsiteThumbnailPath(), "assets/images/nopreview.png");
      $path = null;
    }
    return $path;
  }

  /**
   * Upload uploaded file to s3
   * @param UploadedFile $file uploaded file from form
   */
  public function uploadUploadedFile ($file) {
    $file->move("tmp", $this->file_name);
    $path = "tmp/{$this->file_name}";

    if(is_file($path)) {
      $this->uploadFile($path);
      unlink($path);
    }
  }

  /**
   * Delete file from s3
   * @param string $filename media filename
   */
  public function deleteUploadedFile ($filename) {
    S3::deleteObject($this->getResourcesPath()."/".$filename);
  }

  /**
   * Upload file to s3
   * @param string $path path to file
   */
  public function uploadFile ($path) {
    S3::putObject($this->getFilePath(), $path);
  }

  /**
   * Return source text of a media ( upload|online document|image| source )
   */
  static public function sourceText ($media) {
    $result = '';
    if ( self::IsUploaded($media) ) {
      $result = 'Upload ';
      if ( self::IsTypeImage($media) ) {
        $result .= 'Image Source';
      } elseif ( self::IsTypeDocument($media) ) {
        $result .= 'Document ';
        if ( self::IsFileExt($media, self::EXT_DOC) ) {
          $result .= 'Download';
        } elseif ( self::IsFileExt($media, self::EXT_PDF) ) {
          $result .= 'Source';
        }
      }
    } elseif ( self::IsOnline($media) ) {
      $result = 'Online ';
      if ( self::IsTypeWebsite($media) ) {
        $result .= 'Website Visit';
      } else if ( self::IsTypeVideo($media) ) {
        $result .= 'Video Source';
      } else if ( self::IsTypeImage($media) ) {
        $result .= 'Image Source';
      } else if ( self::IsTypeDocument($media) ) {
        $result .= 'Document ';
        if ( self::IsLinkExt($media, self::EXT_DOC) ) {
          $result .= 'Download';
        } else {
          $result .= 'Source';
        }
      }
    }
    return $result;
  }


  static protected function boot () {
    parent::boot();
    static::deleting (function ($media) {
      $mediaId = $media->id;
      S3::deleteObjects([
        $media->getFilePath(),
        $media->getThumbnailPath(),
        $media->getWebsiteThumbnailPath(),
        $media->getResourcesPath()
      ]);
      BundleCart::deleteMedia($mediaId);
      CollectionDetail::deleteMedia($mediaId);
      Favorite::deleteMedia($mediaId);
      History::deleteMedia($mediaId);
      Learn::deleteMedia($mediaId);
      LikeDislike::deleteMedia($mediaId);
      MediaReport::deleteMedia($mediaId);
    });
  }


  // SERIALIZATION
  //
  protected $appends = ['description_formatted', 'updated_at_formatted', 'type_formatted'];

  public function getDescriptionFormattedAttribute () {
    return limitString($this->attributes['description']);
  }
  public function getUpdatedAtFormattedAttribute () {
    return time_ago($this->attributes['updated_at']);
  }
  public function getTypeFormattedAttribute () {
    return formatMediaType($this->attributes['type']);
  }
  
    /**
     * Get media list for admin
     * Author: Jinandra
     * Date: 07-02-2017
     *
     * @param  string 
     * @return array
     */
    public static function getMediaList( $search_val = "" )
    {
        $mediaList = DB::table((new Media)->getTable().' as M')
          ->select('M.id', 'M.title', 'M.description', 'M.web_link', 'M.file_name', 'M.user_id', 'M.created_at', 'M.type', 'M.private', 'MR.reason','MR.id as mr_id', 'CD.*', 'H.*', 'U.screen_name')
          ->leftJoin(DB::raw('( '.self::qCountCollected().' ) as CD'), function($leftjoin4) {
            $leftjoin4->on(DB::raw('`CD`.`count_collected$media_id`'), '=', 'M.id');
          })
          ->leftJoin(DB::raw('( '.self::qViewCount().' ) as H'), function($leftjoin5) {
            $leftjoin5->on(DB::raw('`H`.`view_count$media_id`'), '=', 'M.id');
          })
          ->leftJoin((new MediaReport)->getTable().' as MR', 'MR.media_id', '=', 'M.id')
          ->join((new User)->getTable().' as U', 'M.user_id', '=', 'U.id')
          ->where(function ($query) use($search_val){
            if($search_val != "")
              $query->where('M.title', 'like', '%'.$search_val.'%');
          })
          ->orderBy('M.id', 'DESC')
          ->paginate(10);
        
        if( $mediaList )
        {
            return $mediaList;
        }
        else{
            return false;
        }
    }


  // === LEFT JOIN QUERY ===

  // List the media and its count collected by a user including the creator, (count_collected$media_id, count_cd)
  // @return String
  static public function qCountCollected () {
    $mediaTable = (new Media)->getTable();
    $collectionDetailTable = (new CollectionDetail)->getTable();
    $query =<<<QUERY
SELECT media.id AS `count_collected\$media_id`, COUNT(cd.collector_id)+1 AS count_cd
FROM {$mediaTable} AS media
LEFT JOIN (
SELECT DISTINCT(user_id) as collector_id, media_id FROM {$collectionDetailTable}
) AS cd ON cd.media_id=media.id AND cd.collector_id!=media.user_id 
GROUP by id
QUERY;
    return $query;
  }

  // List the media and its view count (view_count$media_id, view_count)
  // @return String
  static public function qViewCount () {
    $mediaTable   = (new Media)->getTable();
    $historyTable = (new History)->getTable();
    $query =<<<QUERY
SELECT media.id AS `view_count\$media_id`, COUNT(history.id) AS view_count
FROM {$mediaTable} AS media
LEFT JOIN {$historyTable} AS history ON history.id_media=media.id
GROUP by media.id
QUERY;
    return $query;
  }

  // List the media and its like dislike count & percent (count_like_dislike$media_id, count_like, count_dislike, likePercent, dislikePercent)
  // @return String
  static public function qCountLikeDislike () {
    $likeDislikeTable = (new LikeDislike)->getTable();
    $query =<<<QUERY
SELECT id_media AS `count_like_dislike\$media_id`, SUM(`like`) AS count_like, SUM(`dislike`) AS count_dislike,
CEIL(SUM(`like`) / (SUM(`like`)+SUM(`dislike`))*100) AS likePercent,
CEIL(SUM(`dislike`) / (SUM(`like`)+SUM(`dislike`))*100) AS dislikePercent
FROM {$likeDislikeTable}
GROUP by id_media
QUERY;
    return $query;
  }

  // List the media by its user id (bundle_cart$media_id, id_media_bundle_cart)
  // @return String
  static public function qBundleCart () {
    $bundleCartTable = (new BundleCart)->getTable();
    $query =<<<QUERY
SELECT media_id AS `bundle_cart\$media_id`, media_id AS id_media_bundle_cart
FROM {$bundleCartTable}
QUERY;
    $query .= " WHERE user_id ".( Auth::check() ? " = ".Auth::user()->id : " IS NULL" )." ";
    return $query;
  }

  // List the media by its user id (favorite$media_id, fav)
  // @return String
  static public function qFavorite () {
    $favoriteTable = (new Favorite)->getTable();
    $query =<<<QUERY
SELECT id_media AS `favorite\$media_id`, id_media AS fav
FROM {$favoriteTable}
QUERY;
    $query .= " WHERE user_id ".( Auth::check() ? " = ".Auth::user()->id : " IS NULL" )." ";
    return $query;
  }

  // List the media by its user id (is_like_dislike$media_id, like [1|0|NULL], dislike [1|0|NULL])
  // @return String
  static public function qIsLikeDislike () {
    $likeDislikeTable = (new LikeDislike)->getTable();
    $query =<<<QUERY
SELECT id_media AS `is_like_dislike\$media_id`, `like`, `dislike`
FROM {$likeDislikeTable}
QUERY;

    $query .= " WHERE user_id ".( Auth::check() ? " = ".Auth::user()->id : " IS NULL" )." ";
    return $query;
  }

  // List the media and its last accessed (last_accessed$media_id, lastAccessed)
  // @return String
  static public function qLastAccessed () {
    $historyTable = (new History)->getTable();
    $query =<<<QUERY
SELECT id_media AS `last_accessed\$media_id`, created_at AS lastAccessed
FROM history
QUERY;
    if (Auth::check()) {
      $query .= " WHERE (user_id != '".Auth::user()->id."' OR user_id IS NULL)";
    } else {
      $query .= " WHERE user_id IS NOT NULL";
    }
    $query .= " ORDER BY created_at";
    $query .= " LIMIT 1";
    return $query;
  }

  // Return all media with its additional attributes
  // @return QueryBuilder
  static public function qAll ($onlyPublic = false) {
    $query = DB::table((new Media)->getTable().' as M')
      ->select('M.*', 'USR.email', 'USR.screen_name', 'COC.*', 'VOC.*', 'CLD.*', 'BUC.*', 'FAV.*', 'ILD.*', 'LAC.*')
      ->join((new User)->getTable().' as USR', 'USR.id', '=', 'M.user_id')
      ->leftJoin(DB::raw('( '.Media::qCountCollected().' ) AS COC'), 'COC.count_collected$media_id', '=', 'M.id')
      ->leftJoin(DB::raw('( '.Media::qViewCount().' ) AS VOC'), 'VOC.view_count$media_id', '=', 'M.id')
      ->leftJoin(DB::raw('( '.Media::qCountLikeDislike().' ) AS CLD'), 'CLD.count_like_dislike$media_id', '=', 'M.id')
      ->leftJoin(DB::raw('( '.Media::qBundleCart().' ) AS BUC'), 'BUC.bundle_cart$media_id', '=', 'M.id')
      ->leftJoin(DB::raw('( '.Media::qFavorite().' ) AS FAV'), 'FAV.favorite$media_id', '=', 'M.id')
      ->leftJoin(DB::raw('( '.Media::qIsLikeDislike().' ) AS ILD'), 'ILD.is_like_dislike$media_id', '=', 'M.id')
      ->leftJoin(DB::raw('( '.Media::qLastAccessed().' ) AS LAC'), 'LAC.last_accessed$media_id', '=', 'M.id');
    if ($onlyPublic) {
      $query->whereRaw('M.private = '.self::STATUS_PUBLIC);
    }
    return $query;
  }
}

