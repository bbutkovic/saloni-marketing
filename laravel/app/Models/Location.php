<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\LocationExtras;

class Location extends Model
{
    public $timestamps = false;
    
    public function location_extras() {
        return $this->hasOne('App\Models\LocationExtras');
    }
    
    public function salon() {
        return $this->belongsTo('App\Models\Salons');
    }
    
    public function billing_info() {
        return $this->hasOne('App\Models\BillingLocation', 'id');
    }
    
    public function categories() {
        return $this->hasMany('App\Models\Salon\Category', 'location_id', 'id');
    }
    
    public function services() {
        return $this->hasMany('App\Models\Salon\Service', 'location_id', 'id');
    }
    
    public function photos() {
        return $this->hasMany('App\Models\Salon\LocationPhotos', 'location_id', 'id');
    }

    public function happy_hour_location() {
        return $this->hasMany('App\Models\Salon\HappyHour', 'location_id', 'id');
    }

    public function location_vouchers() {
        return $this->hasMany('App\Models\Salon\Vouchers', 'location_id', 'id');
    }

    public function loyalty_program() {
        return $this->hasOne('App\Models\Salon\LoyaltyPrograms', 'location_id', 'id');
    }

    public function marketing_templates() {
        return $this->hasMany('App\Models\Marketing\MarketingTemplate', 'location_id', 'id');
    }

    public function reminders() {
        return $this->hasMany('App\Models\Marketing\Reminders');
    }

    public function booking() {
        return $this->hasMany('App\Models\Booking\Booking');
    }

    public function location_campaigns() {
        return $this->hasMany('App\Models\Salon\MarketingCampaign', 'location_id', 'id');
    }

    public function stats_date() {
        return $this->hasOne('App\Models\Payments\StatsDate');
    }

    public function work_hours() {
        return $this->hasMany('App\Models\LocalHours', 'location_id', 'id');
    }
}
