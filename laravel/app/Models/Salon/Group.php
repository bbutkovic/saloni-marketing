<?php

namespace App\Models\Salon;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'service_group';
    public $timestamps = false;
    
    public function subcategory() {
        return $this->hasMany('App\Models\Salon\SubCategory');
    }
    
    public function service() {
        return $this->hasMany('App\Models\Salon\Service', 'group', 'id')->orderBy('order', 'ASC');
    }
    
    public function get_category() {
        return $this->belongsTo('App\Models\Salon\Category', 'category_id', 'id');
    }
    
}
