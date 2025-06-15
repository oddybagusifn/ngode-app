<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show()
    {
        $products = Product::all();
        return view('admin/dashboard', compact('products'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'stock' => 'required|integer',
            'price' => 'required|numeric',
            'categories' => 'required|array|min:1',
            'categories.0' => 'exists:categories,id',
        ]);



        do {
            $randomCode = '#ZY' . rand(1000, 9999);
        } while (Product::where('product_code', $randomCode)->exists());

        Product::create([
            'product_code' => $randomCode,
            'name'         => $request->name,
            'stock'        => $request->stock,
            'price'        => $request->price,
            'category_id'  => $request->categories[0],
        ]);



        return redirect()->route('admin.products.show')->with('success', 'Produk berhasil ditambahkan');
    }

    public function category()
    {
        $categories = Category::all();

        return view('admin/create', compact('categories'));
    }
}
