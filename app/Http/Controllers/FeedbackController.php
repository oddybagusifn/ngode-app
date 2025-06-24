<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    // Menampilkan produk dari transaksi terakhir
    public function index()
    {
        $user = Auth::user();

        $recentTransaction = Order::with('products')
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        $purchasedProducts = $recentTransaction ? $recentTransaction->products : [];

        return view('user.feedback', compact('purchasedProducts'));
    }

    // Menyimpan feedback ke database
    public function store(Request $request)
    {
        $user = Auth::user();

        $ratings = $request->input('ratings', []);
        $reviews = $request->input('reviews', []);

        foreach ($ratings as $productId => $rating) {
            $reviewText = $reviews[$productId] ?? null;

            // Simpan feedback
            Feedback::create([
                'user_id'    => $user->id,
                'product_id' => $productId,
                'rating'     => $rating,
                'review'     => $reviewText,
            ]);

            // Hitung rata-rata rating terbaru dan update ke produk
            $averageRating = Feedback::where('product_id', $productId)->avg('rating');
            $product = Product::find($productId);
            $product->rating = $averageRating;
            $product->save();
        }

        return redirect()->route('homepage')->with('success', 'Terima kasih atas umpan balik Anda!');
    }

    // Toggle Like / Unlike
    public function toggleLike($id)
    {
        $feedback = Feedback::findOrFail($id);
        $user = Auth::user();

        $like = $feedback->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            $feedback->likes()->create(['user_id' => $user->id]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'count' => $feedback->likes()->count(),
        ]);
    }



    // Menyimpan komentar
    public function comment(Request $request, $id)
{
    $request->validate([
        'comment' => 'required|string|max:500',
    ]);

    $user = Auth::user();

    $feedback = Feedback::findOrFail($id);

    $comment = $feedback->comments()->create([
        'user_id' => $user->id,
        'comment' => $request->comment,
    ]);

    return response()->json([
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar ? asset($user->avatar) : null,
        ],
        'comment' => $comment->comment,
    ]);
}

}
