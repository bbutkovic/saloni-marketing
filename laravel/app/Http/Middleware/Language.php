<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Languages;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        App::setLocale(Session::get('language'));

        return $next($request);
    }
}
