<?php

namespace Junges\Pix;

use Junges\Pix\Api\Contracts\AuthenticatesPSPs;
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

    /**
     * Defines which callback should be used to generate qr codes.
     *
     * @param string $callback
     */
    public static function generatesQrCodeUsing(string $callback): void
    {
        app()->singleton(GeneratesQrCode::class, $callback);
    }

    /**
     * Defines which callback should be used to authenticate to PSPs.
     *
     * @param string $callback
     */
    public static function authenticatesUsing(string $callback): void
    {
        app()->singleton(AuthenticatesPSPs::class, $callback);
    }

    /**
     * Defines your default PSP.
     *
     * @param string $psp
     */
    public static function useAsDefaultPsp(string $psp = 'default'): void
    {
        Psp::defaultPsp($psp);
    }
}
