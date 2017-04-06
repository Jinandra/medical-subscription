<?php

namespace App\Models;

use DB,
    Auth;

Class Common {

    const SEARCH_SORT_MOST_POPULAR = 'most-popular';
    const SEARCH_SORT_NEWEST = 'newest';
    const SEARCH_SORT_MOST_LIKED = 'most-liked';
    const SEARCH_SORT_MOST_COLLECTED = 'most-collected';
    
    const SEARCH_UPLOAD_ALL_TIME = 'all-time';
    const SEARCH_UPLOAD_TODAY = 'today';
    const SEARCH_UPLOAD_LAST_WEEK = 'last-week';
    const SEARCH_UPLOAD_LAST_MONTH = 'last-month';
    
    const SEARCH_TYPE_ALL = 'all';
    const SEARCH_TYPE_WEBSITE = 'website';
    const SEARCH_TYPE_VIDEO = 'video';
    const SEARCH_TYPE_IMAGE = 'image';
    const SEARCH_TYPE_TEXT = 'text';
    const SEARCH_TYPE_FOLDER = 'folder';
    const SEARCH_TYPE_USER = 'user';

    static public function getAllSortTerms() {
        return [
            self::SEARCH_SORT_MOST_POPULAR => 'Most popular',
            self::SEARCH_SORT_NEWEST => 'Newest',
            self::SEARCH_SORT_MOST_LIKED => 'Most liked',
            self::SEARCH_SORT_MOST_COLLECTED => 'Most collected'
        ];
    }

    static public function getAllSearchDates() {
        return [
            self::SEARCH_UPLOAD_ALL_TIME => 'All time',
            self::SEARCH_UPLOAD_TODAY => 'Today',
            self::SEARCH_UPLOAD_LAST_WEEK => 'Last week',
            self::SEARCH_UPLOAD_LAST_MONTH => 'Last month'
        ];
    }

    static public function getAllSearchTypes() {
        return [
            self::SEARCH_TYPE_ALL => 'All',
            self::SEARCH_TYPE_WEBSITE => 'Website',
            self::SEARCH_TYPE_VIDEO => 'Video',
            self::SEARCH_TYPE_IMAGE => 'Image',
            self::SEARCH_TYPE_TEXT => 'Text',
            self::SEARCH_TYPE_FOLDER => 'Folder',
            self::SEARCH_TYPE_USER => 'User'
        ];
    }

    static public function getAllMediaSearchTypes() {
        return [
            self::SEARCH_TYPE_WEBSITE,
            self::SEARCH_TYPE_VIDEO,
            self::SEARCH_TYPE_IMAGE,
            self::SEARCH_TYPE_TEXT
        ];
    }

    static public function searchCollections($querySearch, $sort = null, $date = null, $user = false) {
      $countCollected = <<<QUERY
SELECT parent.id AS collection_id, COUNT(children.id) AS count_cd
FROM collections AS parent
LEFT JOIN collections AS children ON children.original_id=parent.id
GROUP BY parent.id
QUERY;
        $result = DB::table('collections as c')
                ->join('users as u', 'u.id', '=', 'c.user_id')
                ->leftJoin(DB::raw(
                            "(select cd1.collection_id, cd1.media_id as first_media_id from collection_details cd1 
                            join (
                                select collection_id, max(cd3.updated_at) as max_updated from collection_details cd3
                                join media as m on (cd3.media_id = m.id and m.private = 0)
                                GROUP BY collection_id
                            ) cd2 on (cd1.collection_id = cd2.collection_id and cd1.updated_at = cd2.max_updated)
                            group by cd1.collection_id) as fm" //because there are details with updated_at = '0000-00-00 00:00:00'
                        ), 'fm.collection_id', '=', 'c.id')
                ->leftJoin(DB::raw(
                            "(select cd.*, count(cd.id) media_count from collection_details cd
                            join media m on m.id = cd.media_id
                            group by cd.collection_id) as cm"
                        ), 'cm.collection_id', '=', 'c.id')
                ->leftJoin(DB::raw(
                            "(select cd.*, count(bc.id) bundle_count from collection_details cd
                            join media m on m.id = cd.media_id
                            join bundle_cart bc on (m.id = bc.media_id and bc.user_id = 28 )
                            group by cd.collection_id) as cb"
                        ), 'cb.collection_id', '=', 'c.id')
                ->leftJoin(DB::raw("( {$countCollected} ) AS cd"), "cd.collection_id", "=", "c.id")
                ->select('c.*', DB::raw('DATE(c.created_at) as created_dat'), 'u.screen_name as user_screen_name', 'u.name as user_full_name', 'u.first_name as user_fname', 'u.last_name as user_lname', 'fm.first_media_id', 'cm.media_count', 'cb.bundle_count', 'cd.count_cd AS collected')
                ->whereRaw('c.original_id is null')
                ->whereRaw('c.category_id is null')
                ;
        if ($date == Common::SEARCH_UPLOAD_TODAY) {
            $result = $result->whereRaw("DATE(c.created_at) = '".date('Y-m-d')."'");
        } elseif ($date == Common::SEARCH_UPLOAD_LAST_WEEK) {
            $result = $result->whereRaw('c.created_at BETWEEN DATE_ADD(CURDATE(), INTERVAL -7 day) AND CURDATE()');
        } elseif ($date == Common::SEARCH_UPLOAD_LAST_MONTH) {
            $result = $result->whereRaw('c.created_at BETWEEN DATE_ADD(CURDATE(), INTERVAL -30 day) AND CURDATE()');
        }
        $result = $result->where(function($query) use ($querySearch, $user) {
            if($user) {
                $query->where('u.screen_name', 'like', '%' . $querySearch . '%');
            } else {
                $query->where('c.name', 'like', '%' . $querySearch . '%');
                $query->orWhere('c.description', 'like', '%' . $querySearch . '%');
                $query->orWhere('u.screen_name', 'like', '%' . $querySearch . '%');
            }
//            $query->orWhere('u.name', 'like', '%'.$querySearch.'%');
//            $query->orWhere('u.first_name', 'like', '%'.$querySearch.'%');
//            $query->orWhere('u.last_name', 'like', '%'.$querySearch.'%');
        });
        if ($sort == Common::SEARCH_SORT_MOST_POPULAR) {
            $result = $result->leftJoin('collections_history as ch', 'c.id', '=', 'ch.collection_id')
                    ->groupBy('c.id');
            $orderField = DB::raw('count(ch.id)');
        } elseif ($sort == Common::SEARCH_SORT_MOST_COLLECTED) {
            $orderField = DB::raw('collected');
        } else {
            $orderField = 'c.created_at';
        }
        $result = $result->orderBy($orderField, 'desc')->get();
        return $result;
    }

    static public function searchMedia($querySearch, $sort = null, $date = null, $types = [], $user = false) {
        $result = DB::table('media as m')
                ->join('users as u', 'u.id', '=', 'm.user_id')
                ->select('m.*', 'u.email as email', 'u.screen_name as user_screen_name', 'u.name as user_full_name', 'u.first_name as user_fname', 'u.last_name as user_lname', 'bc.id as bundle_id', 'count_like', 'count_dislike', 'likePercent', 'dislikePercent', 'cd.count_cd AS collected', 'f.id as fav', 'lastAccessed')
                ->leftJoin('bundle_cart as bc', function($join) {
                    $join->on('m.id', '=', 'bc.media_id');
                    $join->on('bc.user_id', '=', DB::raw("'".Auth::user()->id."'"));
                })
                ->leftJoin(DB::raw(
                            "(select id_media, sum(`like`) as count_like, sum(dislike) as count_dislike, ROUND(sum(`like`)/(sum(`like`) + sum(dislike)))*100 as likePercent, ROUND(sum(`dislike`)/(sum(`like`) + sum(dislike)))*100 as dislikePercent 
                            from like_dislike 
                            group by id_media) as ld"
                        ), 'ld.id_media', '=', 'm.id')
                ->leftJoin(DB::raw(
                    "(SELECT media.id AS media_id, COUNT(collection_details.collector_id)+1 AS count_cd
                      FROM media
                      LEFT JOIN (
                        SELECT DISTINCT(user_id) as collector_id, media_id FROM collection_details
                      ) AS collection_details ON collection_details.media_id=media.id AND collection_details.collector_id!=media.user_id 
                      GROUP by id) AS cd"
                ), 'cd.media_id', '=', 'm.id')
                ->leftJoin('favorite as f', function($join) {
                    $join->on('m.id', '=', 'f.id_media');
                    $join->on('f.user_id', '=', DB::raw("'".Auth::user()->id."'"));
                })
                ->leftJoin(DB::raw("(select id_media, count(id) as visits, max(created_at) as lastAccessed from history group by id_media) as h"), 'm.id', '=', 'h.id_media');
        if ($date == Common::SEARCH_UPLOAD_TODAY) {
            $result = $result->whereRaw("DATE(m.created_at) = '".date('Y-m-d')."'");
        } elseif ($date == Common::SEARCH_UPLOAD_LAST_WEEK) {
            $result = $result->whereRaw('m.created_at BETWEEN DATE_ADD(CURDATE(), INTERVAL -7 day) AND CURDATE()');
        } elseif ($date == Common::SEARCH_UPLOAD_LAST_MONTH) {
            $result = $result->whereRaw('m.created_at BETWEEN DATE_ADD(CURDATE(), INTERVAL -30 day) AND CURDATE()');
        }
        if (!in_array(Common::SEARCH_TYPE_ALL, $types) && !empty($types)) {
            $result = $result->whereIn('m.type', $types);
        }
        $result = $result->where(function($query) use ($querySearch, $user) {
                $query->where('m.private', Media::STATUS_PUBLIC);
                if($user) {
                    $query->where('u.screen_name', 'like', '%' . $querySearch . '%');
                } else {
                    $query->where('m.title', 'like', '%' . $querySearch . '%');
                    $query->orWhere('m.description', 'like', '%' . $querySearch . '%');
                    $query->orWhere('u.screen_name', 'like', '%' . $querySearch . '%');
                }
//                $query->orWhere('u.name', 'like', '%'.$querySearch.'%');
//                $query->orWhere('u.first_name', 'like', '%'.$querySearch.'%');
//                $query->orWhere('u.last_name', 'like', '%'.$querySearch.'%');
            })
            ->groupBy('m.id');
        if ($sort == Common::SEARCH_SORT_MOST_POPULAR) {
            $orderField = DB::raw('visits');
        } elseif ($sort == Common::SEARCH_SORT_MOST_LIKED) {
            $orderField = DB::raw('count_like');
        } elseif ($sort == Common::SEARCH_SORT_MOST_COLLECTED) {
            $orderField = DB::raw('collected');
        } else {
            $orderField = 'm.created_at';
        }
        $result = $result->orderBy($orderField, 'desc');
        if ($orderField != 'm.created_at') {
            $result = $result->orderBy('m.created_at', 'desc');
        }
        $result = $result->get();
        return $result;
    }

}
