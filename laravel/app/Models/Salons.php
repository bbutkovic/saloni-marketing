<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salons extends Model
{
    protected $table = 'salons';
    
    public $timestamps = false;
    
    public function locations() {
        return $this->hasMany('App\Models\Location', 'salon_id', 'id');
    }
    
    public function country_name() {
        return $this->hasOne('App\Models\Countries', 'country_identifier', 'country');
    }
    
    public function billing_info() {
        return $this->hasOne('App\Models\BillingSalon', 'id');
    }
    
    public function salon_extras() {
        return $this->hasOne('App\Models\Salon\SalonExtras', 'salon_id', 'id');
    }
    
    public function booking_policy() {
        return $this->hasOne('App\Models\Booking\BookingPolicy', 'salon_id', 'id');
    }
    
    public function booking_fields() {
        return $this->hasMany('App\Models\Booking\BookingFields', 'salon_id', 'id');
    }
    
    public function calendar_options() {
        return $this->hasOne('App\Models\Booking\CalendarOptions', 'salon_id', 'id');
    }
    
    public function client_settings() {
        return $this->hasOne('App\Models\Clients\ClientSettings', 'salon_id', 'id');
    }
    
    public function client_labels() {
        return $this->hasMany('App\Models\Clients\ClientLabels', 'salon_id', 'id');
    }
    
    public function client_referrals() {
        return $this->hasMany('App\Models\Clients\ClientReferrals', 'salon_id', 'id');
    }
    
    public function client_fields() {
        return $this->hasOne('App\Models\Clients\ClientFields', 'salon_id', 'id');
    }
    
    public function website_content() {
        return $this->hasOne('App\Models\Website\WebsiteContent', 'salon_id', 'id');
    }
    
    public function website_images() {
        return $this->hasMany('App\Models\Website\WebsiteImages', 'salon_id', 'id');
    }
    
    public function slider_promos() {
        return $this->hasMany('App\Models\Website\SliderTextBox', 'salon_id', 'id');
    }

    public function salon_currency() {
        return $this->hasOne('App\Models\Salon\Currencies', 'code', 'currency');
    }

    public function fiskal_certificate() {
        return $this->hasOne('App\Models\Payments\FiskalSettings', 'salon_id', 'id');
    }

    public function salon_staff() {
        return $this->hasMany('App\User', 'salon_id', 'id');
    }
}
