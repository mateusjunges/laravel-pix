<?php

namespace Junges\Pix\Facades;

use Illuminate\Support\Facades\Facade;
use Junges\Pix\Api\Features\PayloadLocation\PayloadLocation;

/**
 * Class ApiConsumes.
 *
 * @method static PayloadLocation baseUrl(string $baseUrl);
 * @method static PayloadLocation clientId(string $clientId);
 * @method static PayloadLocation clientSecret(string $clientSecret);
 * @method static PayloadLocation certificate(string $certificate);
 * @method static mixed getOauth2Token();
 * @method static PayloadLocation withFilters($filters);
 * @method static array create(string $loc);
 * @method static array getById(string $id);
 * @method static array detachChargeFromLocation(string $id);
 * @method static array all();
 */
class PayloadLocationFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return PayloadLocation::class;
    }
}
