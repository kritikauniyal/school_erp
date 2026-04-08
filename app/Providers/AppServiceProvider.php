<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('classes')) {
                \Illuminate\Support\Facades\View::share('globalClasses', \App\Models\SchoolClass::orderBy('id', 'asc')->get());
            }
        } catch (\Exception $e) {
            // Silently ignore if DB is not ready during tests/migrations
        }
    }
}
