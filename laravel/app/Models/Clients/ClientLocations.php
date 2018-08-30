<?php

namespace App\Models\Clients;

use Illuminate\Database\Eloquent\Model;

class ClientLocations extends Model
{
    public $timestamps = false;
    
    public function client_obj() {
        return $this->belongsTo('App\Models\Booking\Clients', 'client_id', 'id');
    }
}
