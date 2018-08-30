<?php

namespace App\Models\Salon;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'service_category';
    public $timestamps = false;
    
    public function group() {
        return $this->hasMany('App\Models\Salon\Group');
    }

}
