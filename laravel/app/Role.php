<?php

namespace App;

use Laratrust\Models\LaratrustRole;
use App\Permission;

class Role extends LaratrustRole
{
    public function role_permissions() {
        return $this->hasMany('App\PermissionRole','role_id','id');
    }
}
