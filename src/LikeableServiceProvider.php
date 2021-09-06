<?php

namespace Aabosham\Likeable;

use Illuminate\Support\ServiceProvider;

class LikeableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
