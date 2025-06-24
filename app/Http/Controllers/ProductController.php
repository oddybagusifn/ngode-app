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

    public function search(Request $request)
{
    $keyword = $request->input('keyword');

    $products = Product::where('name', 'like', "%{$keyword}%")
        ->select('id', 'name', 'price', 'image')
        ->get();

    return response()->json(['products' => $products]);
}
}
