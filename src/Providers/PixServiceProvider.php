<?php

namespace Junges\Pix\Providers;

use Illuminate\Support\ServiceProvider;
use Junges\Pix\Contracts\GeneratesQrCodeContract;
use Junges\Pix\QrCodeGenerator;

class PixServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . "/../../routes/laravel-pix-routes.php");
        $this->loadViewsFrom(__DIR__ . "/../../resources/views", 'laravel-pix');

        $this->publishes([
            __DIR__ . "/../../config/laravel-pix.php" => config_path('laravel-pix.php')
        ], 'laravel-pix-config');

        $this->publishes([
            __DIR__ . "/../../public" => public_path('vendor/laravel-pix')
        ],' laravel-pix-assets');

    }

    public function register()
    {
        $this->app->bind(GeneratesQrCodeContract::class, QrCodeGenerator::class);
    }
}