<?php

namespace App\Providers;

use App\Http\Controllers\Product\JsonProductController;
use App\Http\Controllers\Product\ProductController;
use App\Repositories\EloquentProductsRepository;
use App\Repositories\JsonProductsRepository;
use App\Repositories\ProductsRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(ProductController::class)
          ->needs(ProductsRepositoryInterface::class)
          ->give(EloquentProductsRepository::class);

        $this->app->when(JsonProductController::class)
          ->needs(ProductsRepositoryInterface::class)
          ->give(JsonProductsRepository::class);
    }
}
