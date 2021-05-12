<?php

namespace Junges\Pix;

use Junges\Pix\Api\Contracts\AuthenticatesWithOauth;
use Junges\Pix\Contracts\GeneratesQrCode;

class LaravelPix
{
    public static function generatesQrCodeUsing(string $callback): void
    {
        app()->singleton(GeneratesQrCode::class, $callback);
    }

    public static function authenticatesViaOauthUsing(string $callback): void
    {
        app()->singleton(AuthenticatesWithOauth::class, $callback);
    }
}