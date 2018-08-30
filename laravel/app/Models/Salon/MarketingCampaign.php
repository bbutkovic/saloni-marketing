<?php

namespace App\Models\Salon;

use Illuminate\Database\Eloquent\Model;

class MarketingCampaign extends Model
{
    protected $table = 'marketing_campaign';

    public function location() {
        return $this->hasOne('App\Models\Location', 'id', 'location_id');
    }
}
