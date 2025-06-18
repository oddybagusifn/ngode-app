<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $perPage = $request->input('per_page', 5);

        if ($perPage === 'all') {
            $allProducts = Product::latest()->get();

            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $perPage = $allProducts->count();
            $currentItems = $allProducts->forPage($currentPage, $perPage);

            $products = new LengthAwarePaginator(
                $currentItems,
                $allProducts->count(),
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $products = Product::latest()->paginate((int)$perPage)->appends($request->query());
        }

        return view('admin.dashboard', [
            'products' => $products,
            'perPage' => $request->input('per_page', 5),
        ]);
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();

        return view('admin.edit', compact('product', 'categories'));
    }

    public function category()
    {
        $categories = Category::all();

        $chunks = $categories->chunk(ceil($categories->count() / 2));

        return view('admin.create', compact('categories', 'chunks'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'categories' => 'required|array|min:1',
            'categories.0' => 'exists:categories,id',
            'description' => 'required|string|max:1000',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Generate kode unik
        do {
            $randomCode = '#ZY' . rand(1000, 9999);
        } while (Product::where('product_code', $randomCode)->exists());

        $imagePath = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Folder di public/
            $destinationPath = public_path('uploads/product_images');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $imageName);

            $imagePath = 'uploads/product_images/' . $imageName;
        }

        Product::create([
            'product_code' => $randomCode,
            'name'         => $request->name,
            'stock'        => $request->stock,
            'price'        => $request->price,
            'description'  => $request->description,
            'image'        => $imagePath,
            'category_id'  => $request->categories[0],
        ]);

        return redirect()->route('admin.products.show')->with('success', 'Produk berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'stock' => 'required|integer',
            'price' => 'required|numeric',
            'categories' => 'required|array|min:1',
            'categories.0' => 'exists:categories,id',
            'description' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $product = Product::findOrFail($id);

        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/product_images');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $imageName);

            $product->image = 'uploads/product_images/' . $imageName;
        }

        $product->name = $request->name;
        $product->stock = $request->stock;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->category_id = $request->categories[0];

        $product->save();

        return redirect()->route('admin.products.show')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.show')->with('success', 'Produk berhasil dihapus.');
    }

    public function filter(Request $request)
    {
        $search = $request->query('query');
        $perPage = $request->query('per_page', 5);
        $date = $request->query('date');

        $query = Product::with('category')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('product_code', 'LIKE', "%{$search}%")
                        ->orWhere('name', 'LIKE', "%{$search}%")
                        ->orWhere('stock', 'LIKE', "%{$search}%")
                        ->orWhere('price', 'LIKE', "%{$search}%")
                        ->orWhereHas('category', function ($q3) use ($search) {
                            $q3->where('name', 'LIKE', "%{$search}%");
                        });
                });
            });

        if ($date) {
            $query->whereDate('created_at', $date);
        }

        if ($perPage === 'all') {
            $allProducts = $query->get();
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $perPage = $allProducts->count();
            $currentItems = $allProducts->forPage($currentPage, $perPage);

            $products = new LengthAwarePaginator(
                $currentItems,
                $allProducts->count(),
                $perPage,
                $currentPage,
                [
                    'path' => $request->url(),
                    'query' => $request->query(),
                ]
            );
        } else {
            $products = $query->latest()->paginate((int) $perPage)->appends($request->query());
        }

        return view('admin.partials.product-table', compact('products'))->render();
    }

    public function search(Request $request)
    {
        return $this->filter($request);
    }
}
