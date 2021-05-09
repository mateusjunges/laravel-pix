<?php

namespace Junges\Pix\Facades;

use Illuminate\Support\Facades\Facade;
use Junges\Pix\Api\Features\Cob\Cob;

/**
 * Class ApiConsumes
 * @package Junges\Pix\Facades
 * @method static Cob baseUrl(string $baseUrl);
 * @method static Cob clientId(string $clientId);
 * @method static Cob clientSecret(string $clientSecret);
 * @method static Cob certificate(string $certificate);
 * @method static mixed getOauth2Token();
 */
class CobFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return Cob::class;
    }
}