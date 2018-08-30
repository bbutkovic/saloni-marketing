<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Model;

class WebsiteImages extends Model
{
    public $timestamps = false;
    
    public static $slider_rules = [
        'file' => 'dimensions:min_width=1920,min_height=600'
    ];
}
