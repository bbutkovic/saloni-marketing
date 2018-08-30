<?php

namespace App\Models\Salon;

use Illuminate\Database\Eloquent\Model;

class ServiceStaff extends Model
{
    public $timestamps = false;
    protected $table = 'service_staff';
    
    public function service() {
        return $this->belongsTo('App\Models\Salon\Service', 'service_id', 'id');
    }
}
