<?php

namespace Junges\Pix\Facades;

use Illuminate\Support\Facades\Facade;
use Junges\Pix\Api\Features\Cob\Cob;
use Junges\Pix\Api\Features\Cob\CobRequest;

/**
 * Class ApiConsumes.
 *
 * @method static Cob baseUrl(string $baseUrl);
 * @method static Cob clientId(string $clientId);
 * @method static Cob clientSecret(string $clientSecret);
 * @method static Cob certificate(string $certificate);
 * @method static mixed getOauth2Token();
 * @method static Cob withFilters($filters);
 * @method static array create(CobRequest $request);
 * @method static array all();
 * @method static array getByTransactionId(string $transactionId);
 */
class CobFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return Cob::class;
    }
}
