<?php

namespace App\Models\Salon;

use Illuminate\Database\Eloquent\Model;

class ServiceDetails extends Model
{
    public $timestamps = false;
    
    public function service() {
        return $this->belongsTo('App\Models\Salon\Service');
    }
}
