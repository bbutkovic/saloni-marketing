<?php

namespace App\Models\Salon;

use Illuminate\Database\Eloquent\Model;

class StaffVacation extends Model
{
    public $timestamps = false;
    
    public static $vacation_rules = [
        'vacation_start' => 'required',
        'vacation_end' => 'required',
        'note' => 'max:25',
    ];
    
    public function staff() {
        return $this->belongsTo('App\User');
    }
}
