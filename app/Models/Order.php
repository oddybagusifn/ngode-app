<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $fillable = [
        'order_id',
        'user_id',
        'person_name',
        'phone',
        'address',
        'province',
        'city',
        'subdistrict',
        'village',
        'courier',
        'postal_code',
        'delivery_method',
        'payment_method',
        'total_price',
        'status',
    ];


    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function products()
{
    return $this->hasManyThrough(Product::class, OrderDetail::class, 'order_id', 'id', 'id', 'product_id');
}
}
