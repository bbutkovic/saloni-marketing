<?php

namespace App\Models\Booking;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'booking';
    
    public function booking_details() {
        return $this->hasOne('App\Models\Booking\BookingDetails');
    }

    public function booking_location() {
        return $this->hasOne('App\Models\Location', 'id', 'location_id');
    }
    
    public function service() {
        return $this->hasOne('App\Models\Salon\Service', 'id', 'service_id');
    }
    
    public function client() {
        return $this->hasOne('App\Models\Booking\Clients', 'id', 'client_id');
    }
    
    public function staff() {
        return $this->hasOne('App\User', 'id', 'staff_id');
    }

    public function pricing() {
        return $this->hasOne('App\Models\Booking\BookingPrice', 'booking_id', 'type_id');
    }

    public function getDateAttribute()
    {
        return date('d M Y', strtotime($this->attributes['booking_date']));
    }

    public function getStartTimeAttribute()
    {
        return date('H:i', strtotime($this->attributes['start']));
    }

    public function getEndTimeAttribute()
    {
        return date('H:i', strtotime($this->attributes['booking_end']));
    }

}
