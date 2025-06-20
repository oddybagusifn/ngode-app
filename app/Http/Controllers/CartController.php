<?php

namespace App\Http\Controllers;

use id;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{


    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'name'       => 'required|string',
            'price'      => 'required|numeric',
            'image'      => 'required|string',
            'size'       => 'required|string',
            'quantity'   => 'required|integer|min:1',
            'variation'  => 'required|string',
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login untuk menambahkan ke keranjang.');
        }

        if (is_numeric($request->variation)) {
            $variationImage = ProductImage::find($request->variation)?->image_path;
        } elseif (str_starts_with($request->variation, 'main-')) {
            $variationImage = Product::find($request->product_id)?->image;
        } else {
            $variationImage = null;
        }

        $existing = Cart::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->where('variation', $request->variation)
            ->where('size', $request->size)
            ->first();

        if ($existing) {
            $existing->quantity += $request->quantity;
            $existing->save();
        } else {
            Cart::create([
                'user_id'    => $user->id,
                'product_id' => $request->product_id,
                'name'       => $request->name,
                'variation'  => $request->variation,
                'size'       => $request->size,
                'image'      => $variationImage ?? $request->image,
                'quantity'   => $request->quantity,
                'price'      => $request->price,
            ]);
        }
        return back()->with('success', 'Produk berhasil dimasukkan ke keranjang.');
    }

    public function fetch()
    {

        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        return view('components.cart-list', compact('cartItems', 'totalPrice'));
    }

    public function destroy($id)
    {
        $cartItem = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Item tidak ditemukan atau tidak diizinkan.'], 404);
        }

        $cartItem->delete();

        return redirect()->back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}
