<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Systems\CustomRouter;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{


    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
