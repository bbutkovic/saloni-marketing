<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Model;

class LocationSlider extends Model
{
    protected $table = 'location_slider';
    public $timestamps = false;

    public function image() {
        return $this->hasOne('App\Models\Website\WebsiteImages', 'id', 'image_id');
    }
}
