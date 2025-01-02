<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemporaryCart extends Model
{
    protected $table = 'temporary_carts';

    protected $fillable = [
        'user_id',
        'data',
    ];
    protected $casts = [
        'field_name' => 'array'
    ];
}
