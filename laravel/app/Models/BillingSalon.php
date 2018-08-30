<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingSalon extends Model
{
    protected $table = 'salon_billing';

    public $timestamps = false;
    
    public function salon() {
        return $this->belongsTo('App\Models\Salons');
    }
    
    public static $billing_rules = [
        'billing_address' => 'required|max:255',
        'billing_city' => 'required|max:50',
        'billing_zip' => 'required|max:15',
        'billing_address' => 'required|max:255',
        'billing_country' => 'required|max:50',
        'billing_oib' => 'required|min:11|max:11'
    ];

}
