<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    protected $table = 'couriers';
    protected $fillable = [
        'user_id',
        'courier_status_active_id',
        'courier_status_available_id',
    ];

    
    protected $casts = [
        'courier_status_active_id' => 'int',
        'courier_status_available_id'=> 'int'
    ];
}
