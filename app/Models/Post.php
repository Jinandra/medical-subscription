<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;


class Post extends Model
{
    use Sluggable;
    protected $table = 'post';

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public static function getBySlug($slug='')
    {
        return Post::where('slug','=',$slug)->get()->first();
    }

    static public function getNewOrder () {
      $lastPost = Post::where('post_status', 'Publish')->orderBy('sort_order', 'DESC')->first();
      if ( is_null($lastPost) || is_null($lastPost->sort_order) ) { return 0; }
      return $lastPost->sort_order + 1;
    }
}
