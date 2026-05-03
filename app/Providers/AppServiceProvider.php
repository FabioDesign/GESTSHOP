<?php

namespace App\Providers;

use App\Observers\ModelObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\{Cash, Product, Profile, SubCategory, User};

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
        // Fix for MySQL < 5.7.7 and MariaDB < 10.2.2
        Schema::defaultStringLength(255); //Update defaultStringLength
        $models = [
            Cash::class,
            Product::class,
            Profile::class,
            SubCategory::class,
            User::class,
        ];

        foreach ($models as $model) {
            $model::observe(ModelObserver::class);
        }
    }
}
