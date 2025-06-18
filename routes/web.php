<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('landingPage');
});

Route::get('/homepage', [UserController::class, 'homepage'])->name('homepage');
Route::get('/homepage/product/{id}', [UserController::class, 'product'])->name('homepage.product');

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

