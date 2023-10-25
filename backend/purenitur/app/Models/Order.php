<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'cart',
        'price_total',
        'discount_id',
        'final_price',
        'transaction_id',
        'payment_type',
        'status_payment',
        'image_payment',
        'shipment_id',
        'shipment_status',
        'shipment_address',
    ];
}
