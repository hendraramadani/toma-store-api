<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'name',
        'stock',
        'description',
        'cost',
        'product_categorie_id',
        'image',
        'store_id',
    ];

    protected $casts = [
        'product_categorie_id' => 'int',
        'store_id' => 'int',
        'stock' => 'double',
        'cost' => 'double',
    ];
}
