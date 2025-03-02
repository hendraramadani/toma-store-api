<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'address',
        'image',
        'latitude',
        'longitude',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
