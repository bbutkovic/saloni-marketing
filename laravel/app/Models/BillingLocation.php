<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingLocation extends Model
{
    protected $table = 'location_billing';
    public $primaryKey = 'id';
    
    public $timestamps = false;
    
    public function salon() {
        return $this->belongsTo('App\Models\Salons');
    }

    public static $validation_rules = [
        'billing_address' => 'required|max:255',
        'billing_city' => 'required|max:255',
        'billing_zip' => 'required|max:255',
        'billing_country' => 'required',
        'billing_oib' => 'required|min:11|max:11'
    ];
}
