<?php

namespace Junges\Pix;

use Junges\Pix\Api\Contracts\AuthenticatesWithOauth;
use Junges\Pix\Contracts\GeneratesQrCode;
use Junges\Pix\Providers\PixServiceProvider;

class LaravelPix
{
    public static function validatingSslCertificate(bool $validate = true): void
    {
        PixServiceProvider::$verifySslCertificate = $validate;
    }

    public static function withoutVerifyingSslCertificate(): void
    {
        self::validatingSslCertificate(false);
    }

    public static function generatesQrCodeUsing(string $callback): void
    {
        app()->singleton(GeneratesQrCode::class, $callback);
    }

    public static function authenticatesViaOauthUsing(string $callback): void
    {
        app()->singleton(AuthenticatesWithOauth::class, $callback);
    }
}
