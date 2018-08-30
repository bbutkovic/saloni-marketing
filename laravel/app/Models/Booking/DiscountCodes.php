<?php

namespace App\Models\Booking;

use Illuminate\Database\Eloquent\Model;

class DiscountCodes extends Model
{
    public $timestamps = false;
    
    public static $slow_day_validation = [
        'coupon_amount' => 'required|integer',
        'discount' => 'required|integer',
    ];
}