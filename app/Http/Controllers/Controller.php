<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use DB,
    Auth;
use App\Models\Collection;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function getCollections () {
      if ( Auth::check() ) {
        return Collection::listMine();
      }
      return array();
    }

    protected function getUserFavorites () {
      if ( Auth::check() ) {
        return DB::table('favorite')->where('user_id', Auth::user()->id)->get();
      }
      return [];
    }

    protected function noCacheControlHeader () {
      return 'no-cache, max-age=0, must-revalidate, no-store';
    }
}
