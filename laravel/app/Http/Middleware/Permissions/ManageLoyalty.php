<?php

namespace App\Http\Middleware\Permissions;

use Closure;
use Illuminate\Support\Facades\Auth;

class ManageLoyalty
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
        if (!Auth::user()->can('manage-loyalty'))
        {
            return redirect('dashboard')->with('error_message', trans('main.access_denied'));
        }
        return $next($request);
    }
}
