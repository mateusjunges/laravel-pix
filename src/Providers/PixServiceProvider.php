<?php

namespace Junges\Pix\Providers;

use Illuminate\Support\ServiceProvider;
use Junges\Pix\Contracts\GeneratesQrCodeContract;
use Junges\Pix\QrCodeGenerator;

class PixServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    public function register()
    {
        $this->app->bind(GeneratesQrCodeContract::class, QrCodeGenerator::class);
    }
}