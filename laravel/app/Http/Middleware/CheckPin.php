<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Salons;
use Illuminate\Support\Facades\Auth;

class CheckPin {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null) {
        
        $salon = Salons::where('id', Auth::user()->salon_id)->first();
        
        if($salon != null) {
        
            if ((!Auth::user()->hasRole('salonadmin')) && ($salon->salon_extras->require_pin == 1) && (Auth::user()->check_in === null)) {
                return redirect('dashboard');
            }
            return $next($request);
            
        } else {
            return $next($request);
        }
    }
}
