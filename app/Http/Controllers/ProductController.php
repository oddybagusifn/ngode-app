<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getFeaturedProducts($limit = 5)
    {
        return Product::latest()->take($limit)->get();
    }
}
