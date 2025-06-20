<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $fillable = [
        'user_id',
        'full_name',
        'phone_number',
        'address',
        'postal_code',
        'additional_info',
        'delivery_type',
        'payment_method',
        'product_price',
        'packaging_fee',
        'shipping_fee',
        'service_fee',
        'discount',
        'total_price',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
