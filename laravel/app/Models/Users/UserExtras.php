<?php

namespace App\Models\Users;

use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\Model;
use App\User;

class UserExtras extends Model
{
    use Encryptable;

    protected $encryptable = [
        'first_name', 'last_name', 'birthday', 'phone_number',
        'address', 'city', 'state', 'zip', 'country', 'gender'
    ];

    protected $table = 'user_extras';
    
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function user() {
        return $this->hasOne('User');
    }
}
