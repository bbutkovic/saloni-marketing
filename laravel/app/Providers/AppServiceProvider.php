<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Support\Facades\View;
use App\Repositories\InfoRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Schema::defaultStringLength(191);

        $this->app['events']->listen(Authenticated::class, function ($e) {

            //set default user role
            $this->user_role = trans('main.user');

            if ($e->user->hasRole('superadmin')) {
                $this->menu = 'menu.superAdmin';
                $this->user_role = trans('main.superadmin');
                $this->user_role_id = 1;
            } else if ($e->user->hasRole('salonadmin')) {
                $this->menu = 'menu.salon';
                $this->user_role = trans('main.salonadmin');
                $this->user_role_id = 2;
            } else if ($e->user->hasRole('staff')) {
                $this->menu = 'menu.salon';
                $this->user_role = trans('main.salon');
                $this->user_role_id = 3;
            } else if ($e->user->hasRole('trainee')) {
                $this->menu = 'menu.salon';
                $this->user_role = trans('main.salon');
                $this->user_role_id = 4;
            } else if ($e->user->hasRole('supervisor')) {
                $this->menu = 'menu.salon';
                $this->user_role = trans('main.salon');
                $this->user_role_id = 5;
            } else if ($e->user->hasRole('reception')) {
                $this->menu = 'menu.salon';
                $this->user_role = trans('main.salon');
                $this->user_role_id = 6;
            } else if ($e->user->hasRole('user')) {
                $this->menu = 'menu.user';
                $this->user_role = trans('main.user');
                $this->user_role_id = 7;
            }
            
            $info_repo = new InfoRepository;
            $lang_list = $info_repo->getLanguageList();
            $location_list = $info_repo->getLocationList(Auth::user()->salon_id);
            
            $user = Auth::user();
            
            View::share('user', $user);
            View::share('locations_admin', $location_list);
            View::share('menu', $this->menu);
            View::share('languages', $lang_list);
            View::share('salon_id_check', Auth::user()->salon_id);
            //set username
            $this->username = $e->user->email;

            //share username, user role and user role id with all views
            View::share('username', $this->username);
            View::share('user_role', $this->user_role);
            View::share('user_role_id', $this->user_role_id);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
