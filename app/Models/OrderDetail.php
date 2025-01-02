<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'orders_detail';

    protected $fillable = [
        'orders_id',
        'product_id',
        'amount',
        'cost'
    ];


    protected $casts = [
        'amount' => 'int',
        'cost'=> 'int'
    ];

}
