<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function homepage(ProductController $productController)
    {
        $products = $productController->getFeaturedProducts(8);
        // dd($products);
        return view('user/homepage', compact('products'));
    }

    public function product($id)
    {
        $products = Product::with('category', 'feedbacks')->findOrFail($id);
        $recomendedProducts = Product::where('id', '!=', $id)->inRandomOrder()->limit(4)->get();

        return view('user/product', compact('products', 'recomendedProducts'));
    }


    public function searchAjax(Request $request)
    {
        $keyword = $request->query('keyword');

        $products = Product::with('feedbacks')
            ->where('name', 'like', "%{$keyword}%")
            ->get();

        return response()->json($products);
    }


    public function filterByCategory(Request $request)
    {
        $categoryId = $request->input('category');

        $products = Product::with('feedbacks')
            ->where('category_id', $categoryId)
            ->get();

        return response()->json($products);
    }
}
