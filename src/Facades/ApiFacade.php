<?php

namespace Junges\Pix\Facades;

use Illuminate\Support\Facades\Facade;
use Junges\Pix\Api\Api;

/**
 * Class ApiConsumes.
 *
 * @method static Api baseUrl(string $baseUrl);
 * @method static Api clientId(string $clientId);
 * @method static Api clientSecret(string $clientSecret);
 * @method static Api certificate(string $certificate);
 * @method static mixed getOauth2Token();
 */
class ApiFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return Api::class;
    }
}
