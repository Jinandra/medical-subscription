<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use App\Models\BundleCart;
use DB;
use Auth;

class SiteController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index () {
      return view('beta.index', ['medias' => Media::newlyAdded($this->getUserFavorites())]);
    }

    public function newly () {
      if (Auth::check()) {
        return view('beta.uCodeHome.uCodeUser', [
          'medias' => Media::newlyAdded($this->getUserFavorites()),
          'countBundleCart' => BundleCart::getBundleCartCount(Auth::user()->id)
        ]);
      } else {
        return view('beta.uCodeHome.uCode', ['medias' => Media::newlyAdded()]);
      }
    }

    public function daily() {
      $cMedia = new Media();
      if (Auth::check()) {
          $pop1day = $cMedia->popular1Day($this->getUserFavorites());

          return view('beta.uCodeHome.uCodeUser', [
            'medias' => $pop1day,
            'countBundleCart' => BundleCart::getBundleCartCount(Auth::user()->id)
          ]);
      } else {
          $pop1day = $cMedia->popular1Day();
          return view('beta.uCodeHome.uCode', ['medias' => $pop1day]);
      }
    }

    public function weekly() {
        $cMedia = new Media();
        if (Auth::check()) {
            $pop1week = $cMedia->popular1Week($this->getUserFavorites());
            return view('beta.uCodeHome.uCodeUser', [
              'medias' => $pop1week,
              'countBundleCart' => BundleCart::getBundleCartCount(Auth::user()->id)
            ]);
        } else {
            $pop1weeks = $cMedia->popular1Week();
            return view('beta.uCodeHome.uCode', ['medias' => $pop1weeks]);
        }
    }

    public function monthly() {
        $cMedia = new Media();
        if (Auth::check()) {
            $pop1month = $cMedia->popular1Month($this->getUserFavorites());
            return view('beta.uCodeHome.uCodeUser', [
              'medias' => $pop1month,
              'countBundleCart' => BundleCart::getBundleCartCount(Auth::user()->id)
            ]);
        } else {
            $pop1month = $cMedia->popular1Month();
            return view('beta.uCodeHome.uCode', ['medias' => $pop1month]);
        }
    }

}
