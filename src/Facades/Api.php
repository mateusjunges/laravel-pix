<?php

namespace Junges\Pix\Facades;

use Illuminate\Support\Facades\Facade;
use Junges\Pix\Api as PixApi;

/**
 * Class Api
 * @package Junges\Pix\Facades
 * @method static \Junges\Pix\Api baseUrl(string $baseUrl);
 * @method static \Junges\Pix\Api clientId(string $clientId);
 * @method static \Junges\Pix\Api clientSecret(string $clientSecret);
 * @method static \Junges\Pix\Api certificate(string $certificate);
 */
class Api extends Facade
{
    public static function getFacadeAccessor()
    {
        return PixApi::class;
    }
}