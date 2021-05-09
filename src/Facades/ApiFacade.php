<?php

namespace Junges\Pix\Facades;

use Illuminate\Support\Facades\Facade;
use Junges\Pix\Api\Api as PixApi;
use Junges\Pix\Api\CobRequest;

/**
 * Class ApiConsumes
 * @package Junges\Pix\Facades
 * @method static PixApi baseUrl(string $baseUrl);
 * @method static PixApi clientId(string $clientId);
 * @method static PixApi clientSecret(string $clientSecret);
 * @method static PixApi certificate(string $certificate);
 * @method static mixed getOauth2Token();
 */
class ApiFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return PixApi::class;
    }
}