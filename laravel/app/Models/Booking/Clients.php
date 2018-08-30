<?php

namespace App\Models\Booking;

use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Clients extends Model
{
    use Notifiable;
    use Encryptable;

    protected $encryptable = [
        'first_name', 'last_name', 'email', 'phone', 'address',
        'gender', 'city', 'zip', 'birthday', 'custom_field_1',
        'custom_field_2', 'custom_field_3', 'custom_field_4',
        'note'
    ];

    public static $client_validation_rules = [
        'phone' => 'nullable|max:255',
        'email' => 'nullable|max:255',
    ];        

    public function client_label() {
        return $this->hasOne('App\Models\Clients\ClientLabels', 'id', 'label');
    }
    
    public function client_locations() {
        return $this->hasOne('App\Models\Location', 'id', 'location_id');
    }
    
    public function client_extras() {
        return $this->hasMany('App\Models\Clients\ClientExtras', 'client_id', 'id');
    }

    public function account() {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

}
