<?php

namespace Junges\Pix\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Junges\Pix\Api\Api;
use Junges\Pix\Api\Features\Cob\Cob;
use Junges\Pix\Api\Features\Cobv\Cobv;
use Junges\Pix\Api\Features\PayloadLocation\PayloadLocation;
use Junges\Pix\Api\Features\ReceivedPix\ReceivedPix;
use Junges\Pix\Api\Features\Webhook\Webhook;
use Junges\Pix\Contracts\GeneratesQrCode;
use Junges\Pix\Facades\ApiFacade;
use Junges\Pix\Facades\CobFacade;
use Junges\Pix\Facades\CobvFacade;
use Junges\Pix\Facades\PayloadLocationFacade;
use Junges\Pix\Facades\ReceivedPixFacade;
use Junges\Pix\Facades\WebhookFacade;
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
        ],'laravel-pix-assets');

        Blade::directive('laravelPixAssets',function() {
            $path = asset('vendor/laravel-pix/css/app.css');

            return "<link rel='stylesheet' href='{$path}'>";
        });
    }

    public function register()
    {
        $this->app->bind(GeneratesQrCode::class, QrCodeGenerator::class);

        $this->app->bind(ApiFacade::class, Api::class);
        $this->app->bind(CobFacade::class, Cob::class);
        $this->app->bind(CobvFacade::class, Cobv::class);
        $this->app->bind(WebhookFacade::class, Webhook::class);
        $this->app->bind(PayloadLocationFacade::class, PayloadLocation::class);
        $this->app->bind(ReceivedPixFacade::class, ReceivedPix::class);
    }
}