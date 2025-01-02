<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourierStatusActive extends Model
{
    protected $table = 'courier_status_actives';
    protected $fillable = [
        'status',
    ];
}
