<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\GoogleAuthController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

Route::get('/', function () {
    return view('landingPage');
});

Route::get('/homepage', [UserController::class, 'homepage'])->name('homepage');
Route::get('/homepage/product/{id}', [UserController::class, 'product'])->name('homepage.product');


 Route::get('/register', [RegisterController::class, 'index']);
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});


Route::middleware(['web'])->group(function () {
    Route::get('/auth/redirect/google', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');
});


Route::get('/profile', [ProfileController::class, 'index'])->name('profile.page');


// middleware admin
Route::middleware(['auth', RoleMiddleware::class . ':admin'])->group(function () {

    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.products.show');

    Route::get('/admin/create', [AdminController::class, 'category']);

    Route::post('/admin/product-store', [AdminController::class, 'store'])->name('admin.product.store');

    Route::get('/admin/edit/{id}', [AdminController::class, 'edit'])->name('admin.product.edit');

    Route::delete('/admin/products/destroy/{id}', [AdminController::class, 'destroy'])->name('admin.product.destroy');

    Route::put('admin/products/{id}', [AdminController::class, 'update'])->name('admin.product.update');

    Route::get('/admin/products/filter', [AdminController::class, 'filter'])->name('admin.product.filter');

    Route::get('/admin/products/data', [AdminController::class, 'filter']);

    Route::get('/admin/products/search', [AdminController::class, 'search'])->name('admin.product.search');
});


Route::post('/cart/store', [CartController::class, 'store'])->name('cart.store')->middleware('auth');
Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.delete');
Route::get('/cart/fetch', [CartController::class, 'fetch'])->name('cart.fetch');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::get('/checkout/kabupaten', [CheckoutController::class, 'getKabupaten'])->name('checkout.kabupaten');
Route::get('/checkout/kecamatan', [CheckoutController::class, 'getKecamatan'])->name('checkout.kecamatan');
Route::get('/checkout/kelurahan', [CheckoutController::class, 'getKelurahan'])->name('checkout.kelurahan');
Route::get('/checkout/list-kurir', [CheckoutController::class, 'listKurir'])
    ->name('checkout.listKurir');


Route::post('/checkout/store', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/payment', [MidtransController::class, 'pay'])->name('checkout.payment.snap');

Route::post('/midtrans/callback', [MidtransController::class, 'callback'])
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->name('midtrans.callback');


Route::get('/checkout/success', [FeedbackController::class, 'index'])->name('checkout.success');

Route::get('/checkout/cancel', function () {
    return view('user.checkout-cancel'); // Buat file view ini
})->name('checkout.cancel');

Route::get('/checkout/failed', function () {
    return view('user.checkout-failed'); // Buat file view ini
})->name('checkout.failed');


Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.page');

Route::post('/feedback/store', [FeedbackController::class, 'store'])->name('feedback.store');

Route::post('/feedback/{id}/like', [FeedbackController::class, 'like'])->name('feedback.like');
Route::post('/feedback/{id}/comment', [FeedbackController::class, 'comment'])->name('feedback.comment');

Route::post('/feedback/{id}/toggle-like', [FeedbackController::class, 'toggleLike'])->name('feedback.toggleLike');

Route::get('/search', [ProductController::class, 'search'])->name('products.search');

Route::get('/search/products', [UserController::class, 'searchAjax'])->name('products.search.ajax');

Route::get('/filter/products', [UserController::class, 'filterByCategory']);


Route::get('/debug-csrf', function () {
    return response()->json([
        'csrf_token' => csrf_token(),
        'session_token' => session()->token(),
    ]);
});





