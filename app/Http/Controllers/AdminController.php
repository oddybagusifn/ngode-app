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
    'name'         => 'required|string|max:255',
    'stock'        => 'required|integer|min:0',
    'price'        => 'required|numeric|min:0',
    'categories'   => 'required|array|min:1',
    'categories.0' => 'exists:categories,id',
    'description'  => 'required|string|max:1000',
    'image'        => 'nullable|image|max:10240',         // 10MB dengan MIME otomatis
    'thumbnails'   => 'nullable|array|max:5',
    'thumbnails.*' => 'image|max:10240',                  // 10MB dengan MIME otomatis
]);


        // Generate kode unik
        do {
            $randomCode = '#ZY' . rand(1000, 9999);
        } while (Product::where('product_code', $randomCode)->exists());

        // Simpan gambar utama
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imageName = time() . '_main_' . uniqid() . '.' . $imageFile->getClientOriginalExtension();
            $destination = public_path('uploads/product_images');

            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $imageFile->move($destination, $imageName);
            $imagePath = 'uploads/product_images/' . $imageName;
        }

        // Simpan gambar thumbnail
        $thumbnailPaths = [];
        foreach ($request->file('thumbnails') as $file) {
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($destination, $fileName);
            $thumbnailPaths[] = 'uploads/product_images/' . $fileName;
        }

        // Simpan produk
        $product = Product::create([
            'product_code' => $randomCode,
            'name'         => $request->name,
            'stock'        => $request->stock,
            'price'        => $request->price,
            'description'  => $request->description,
            'image'        => $imagePath, // ← Gambar utama disimpan di sini
            'category_id'  => $request->categories[0],
        ]);

        // Simpan gambar-gambar tambahan ke tabel product_images
        foreach ($thumbnailPaths as $path) {
            $product->images()->create([
                'image_path' => $path,
            ]);
        }

        return redirect()->route('admin.products.show')->with('success', 'Produk berhasil ditambahkan.');
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'stock'        => 'required|integer|min:0',
            'price'        => 'required|numeric|min:0',
            'categories'   => 'required|array|min:1',
            'categories.0' => 'exists:categories,id',
            'description'  => 'required|string|max:1000',
            'image'        => 'nullable|image|max:10240',         // 10MB dengan MIME otomatis
            'thumbnails'   => 'nullable|array|max:5',
            'thumbnails.*' => 'image|max:10240',                  // 10MB dengan MIME otomatis
        ]);


        $product = Product::findOrFail($id);

        // ✅ Ganti gambar utama jika ada
        if ($request->hasFile('image')) {
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/product_images'), $imageName);

            $product->image = 'uploads/product_images/' . $imageName;
        }

        // ✅ Hapus thumbnail lama jika ada thumbnail baru diupload
        if ($request->hasFile('thumbnails')) {
            foreach ($product->images as $oldImage) {
                $oldPath = public_path($oldImage->image_path);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
                $oldImage->delete();
            }

            foreach ($request->file('thumbnails') as $file) {
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/product_images'), $fileName);

                $product->images()->create([
                    'image_path' => 'uploads/product_images/' . $fileName,
                ]);
            }
        }

        // ✅ Update field lainnya
        $product->name         = $request->name;
        $product->stock        = $request->stock;
        $product->price        = $request->price;
        $product->description  = $request->description;
        $product->category_id  = $request->categories[0];
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
