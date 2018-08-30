<?php

namespace App\Http\Middleware\Permissions;

use Closure;
use Illuminate\Support\Facades\Auth;
use Session;

class RestrictAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if(Auth::user()->hasRole('superadmin')) {
            return $next($request);
        } else if (Auth::user()->salon_id != Session::get('salon_id')) {
            Session::put('salon_id', Auth::user()->salon_id);
            return redirect('dashboard')->with('error_message', trans('main.access_denied'));
        } else {
            return $next($request);
        }
        
    }
}