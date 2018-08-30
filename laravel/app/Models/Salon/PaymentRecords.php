<?php

namespace App\Models\Salon;

use Illuminate\Database\Eloquent\Model;

class PaymentRecords extends Model
{
    public function payment_extras() {
        return $this->hasOne('App\Models\Salon\PaymentRecordExtras', 'payment_id', 'id');
    }
}
