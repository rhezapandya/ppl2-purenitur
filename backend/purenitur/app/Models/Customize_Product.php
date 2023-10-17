<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customize_Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_product',
        'user_id',
        'cabinets_cart',
        'beds_cart',
        'total_price',
    ];
}
