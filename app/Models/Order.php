<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'total_cost',
        'courier_id',
        'status_order_id',
    ];
    protected $casts = [
        'total_cost' => 'int',
    ];
}
