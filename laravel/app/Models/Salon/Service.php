<?php

namespace App\Models\Salon;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'service';
    public $timestamps = false;
    
    public function service_details() {
        return $this->hasOne('App\Models\Salon\ServiceDetails');
    }
    
    public function service_staff() {
        return $this->hasMany('App\Models\Salon\ServiceStaff');
    }
    
    public function service_category() {
        return $this->belongsTo('App\Models\Salon\Category', 'category', 'id');
    }
    
    public function service_group() {
        return $this->belongsTo('App\Models\Salon\Group', 'group', 'id');
    }
    
    public function service_subgroup() {
        return $this->belongsTo('App\Models\Salon\SubCategory', 'sub_group', 'id');
    }
    
    public static $service_validation = [
        'service_name' => 'required',
        'service_length' => 'required',
        'service_cost' => 'required',
    ];
}