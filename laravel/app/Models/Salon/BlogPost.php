<?php

namespace App\Models\Salon;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    public function post_images() {
        return $this->hasMany('App\Models\Salon\BlogImages', 'post_id', 'id');
    }
}
