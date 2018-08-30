<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static $user_rules = [
        'first_name' => 'required|max:255',
        'last_name' => 'required|max:255',
        'email' => 'required|email|unique:users,email|max:255',
        'password' => 'required|confirmed|min:6|max:255',
    ];
    
    public static $staff_rules = [
        'first_name' => 'required|max:255',
        'last_name' => 'required|max:255',
        'email' => 'required|email|unique:users,email|max:255',
        'password' => 'required|min:6|max:255',
    ];
    
    public static $user_rules_administration = [
        'user_email' => 'required|email|max:255',
        'password' => 'required|confirmed|min:6|max:255',
        'first_name' => 'required|max:255',
        'last_name' => 'required|max:255',
        'user_role' => 'required|exists:roles,id'
    ];
    
    public static $user_avatar = [
        'user_avatar' => 'required|dimensions:min_width=100,min_height=100'
    ];
    
    public static $user_email = [
        'email' => 'required|email|unique:users,email|max:255',
    ];
    
    public static $user_security = [
        'password' => 'required|confirmed|min:6|max:255',
    ];

    public function user_extras() {
        return $this->hasOne('App\Models\Users\UserExtras', 'user_id', 'id');
    }
    
    public function salon() {
        return $this->hasOne('App\Models\Salons', 'id', 'salon_id');
    }
    
    public function location() {
        return $this->hasOne('App\Models\Location', 'id', 'location_id');
    }
    
    public function services() {
        return $this->hasMany('App\Models\StaffServices', 'user_id', 'id');
    }

    public function staff_hours() {
        return $this->hasMany('App\Models\Salon\StaffHours', 'staff_id', 'id');
    }
    
    public function changes() {
        return $this->hasMany('App\Models\Salon\ScheduleChanges', 'staff_id', 'id');
    }
    
    public function check_in() {
        return $this->hasOne('App\Models\Salon\CheckIn');
    }
    
    public function vacation() {
        return $this->hasMany('App\Models\Salon\StaffVacation');
    }
    
    public function schedule_options() {
        return $this->hasMany('App\Models\Salon\UserScheduleOptions', 'staff_id', 'id');
    }
    
    public function service() {
        return $this->hasMany('App\Models\Salon\ServiceStaff', 'user_id', 'id');
    }
    
    public function weekly_schedule() {
        return $this->hasMany('App\Models\Salon\WeeklySchedule', 'user_id', 'id');
    }

    public function privacy_settings() {
        return $this->hasOne('App\Models\Users\PrivacySettings', 'user_id', 'id');
    }

    public function calendar_exports() {
        return $this->hasMany('App\Models\CalendarExports', 'user_id', 'id');
    }
}
