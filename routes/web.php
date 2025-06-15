<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\GoogleAuthController;

Route::get('/', function () {
    return view('landingPage');
});

Route::get('/register', [RegisterController::class, 'index']);
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::get('/login', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'store'])->name('login.store');

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});


Route::get('/auth/redirect/google', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

// middleware admin
Route::middleware(['auth', RoleMiddleware::class . ':admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index']);

    Route::get('/admin', [ProductController::class, 'show'])->name('admin.products.show');

    Route::get('/admin/create', [ProductController::class, 'category']);

    Route::post('/admin/product-store', [ProductController::class, 'store'])->name('admin.product.store');

});

Route::get('/homepage', [HomepageController::class, 'index'])->name('homepage');
