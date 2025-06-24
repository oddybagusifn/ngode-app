<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $count = 0;

            if (Auth::check()) {
                $count = Cart::where('user_id', Auth::id())->count();
            }

            $view->with('cartItemsCount', $count);
        });
    }
}
