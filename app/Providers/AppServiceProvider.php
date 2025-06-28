<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // <-- Add this import line

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // THE FIX: This line tells Laravel to use a simple pagination view
        // that will work with our custom CSS instead of Tailwind CSS.
        Paginator::useBootstrap();
    }
}
