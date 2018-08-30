<?php

namespace App\Models\Salon;

use Illuminate\Database\Eloquent\Model;

class CustomFields extends Model
{
    public $timestamps = false;
    
    public function select_options() {
        return $this->hasMany('App\Models\Salon\SelectOptions', 'field_id', 'id');
    }
}
