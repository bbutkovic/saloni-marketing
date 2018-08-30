<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class UserLocations extends Model
{
    public $timestamps = false;
    
    public static function $rules = [
        'email' => 'required|email|max:255',
        'password' => 'required|min:6',
    ];
    
    public function location() {
        return $this->hasOne('App\Models\Location', 'id');
    }
}
