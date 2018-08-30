<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Model;

class WebsiteContent extends Model {
    
    protected $table = 'website_content';
    
    public $timestamps = false;
    
    public static $about_image_rules = [
        'image' => 'dimensions:min_width=400,min_height=650'
    ];
}
