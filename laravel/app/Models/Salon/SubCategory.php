<?php

namespace App\Models\Salon;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'service_subcategory';
    public $timestamps = false;
    
    public function service() {
        return $this->hasMany('App\Models\Salon\Service', 'sub_group', 'id')->orderBy('order', 'ASC');
    }
    
    public function get_group() {
        return $this->belongsTo('App\Models\Salon\Group', 'group_id', 'id');
    }
}
