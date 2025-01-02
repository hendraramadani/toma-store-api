<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourierStatusAvailable extends Model
{
    protected $table = 'courier_status_availables';
    protected $fillable = [
        'status',
    ];
}
