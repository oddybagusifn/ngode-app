<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_code',
        'name',
        'category_id',
        'stock',
        'price',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
