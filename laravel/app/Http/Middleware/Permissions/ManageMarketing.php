<?php

namespace App\Http\Middleware\Permissions;

use Closure;
use Illuminate\Support\Facades\Auth;

class ManageMarketing
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
        if (!Auth::user()->can('manage-marketing'))
        {
            if(!Auth::user()->hasRole('superadmin'))
                return redirect('dashboard')->with('error_message', trans('main.access_denied'));
        }
        return $next($request);
    }
}
