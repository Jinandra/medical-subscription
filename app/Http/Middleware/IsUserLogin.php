<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\homeController;

class IsUserLogin {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $user = $request->user();
//        return $user;
        if (isset($user->id)) {
            return redirect()->action('HomeController@index');
        }
        return $next($request);
    }

}
