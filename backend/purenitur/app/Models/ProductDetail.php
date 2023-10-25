<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_product',
        'sku',
        'feature_1',
        'feature_2',
        'feature_3',
        'feature_4',
    ];
}
