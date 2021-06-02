<?php

namespace Junges\Pix\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Junges\Pix\Api\Api;
use Junges\Pix\Api\Auth;
use Junges\Pix\Api\Resources\Cob\Cob;
use Junges\Pix\Api\Resources\Cobv\Cobv;
use Junges\Pix\Api\Resources\PayloadLocation\PayloadLocation;
use Junges\Pix\Api\Resources\ReceivedPix\ReceivedPix;
use Junges\Pix\Api\Resources\Webhook\Webhook;
use Junges\Pix\Facades\ApiFacade;
use Junges\Pix\Facades\CobFacade;
use Junges\Pix\Facades\CobvFacade;
use Junges\Pix\Facades\PayloadLocationFacade;
use Junges\Pix\Facades\ReceivedPixFacade;
use Junges\Pix\Facades\WebhookFacade;
use Junges\Pix\LaravelPix;
use Junges\Pix\QrCodeGenerator;

class PixServiceProvider extends ServiceProvider
{
    public static bool $verifySslCertificate = false;

    public function boot()
    {
        $this->registerRoutes();

        $this->registerViews();

        $this->publishFiles();

        $this->bootBladeDirectives();
    }

    public function register()
    {
        LaravelPix::generatesQrCodeUsing(QrCodeGenerator::class);
        LaravelPix::authenticatesUsing(Auth::class);

        LaravelPix::useAsDefaultPsp('default');

        $this->registerFacades();
    }

    private function publishFiles(): void
    {
        $this->publishes([
            __DIR__.'/../../config/laravel-pix.php' => config_path('laravel-pix.php'),
        ], 'laravel-pix-config');

        $this->publishes([
            __DIR__.'/../../public' => public_path('vendor/laravel-pix'),
        ], 'laravel-pix-assets');
    }

    private function bootBladeDirectives(): void
    {
        Blade::directive('laravelPixAssets', function () {
            $path = asset('vendor/laravel-pix/css/app.css');

            return "<link rel='stylesheet' href='{$path}'>";
        });
    }

    private function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/laravel-pix-routes.php');
    }

    private function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'laravel-pix');
    }

    private function registerFacades(): void
    {
        $this->app->bind(ApiFacade::class, Api::class);
        $this->app->bind(CobFacade::class, Cob::class);
        $this->app->bind(CobvFacade::class, Cobv::class);
        $this->app->bind(WebhookFacade::class, Webhook::class);
        $this->app->bind(PayloadLocationFacade::class, PayloadLocation::class);
        $this->app->bind(ReceivedPixFacade::class, ReceivedPix::class);
    }
}
