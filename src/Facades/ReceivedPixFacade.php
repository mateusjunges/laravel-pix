<?php

namespace Junges\Pix\Facades;

use Illuminate\Support\Facades\Facade;
use Junges\Pix\Api\Features\ReceivedPix\ReceivedPix;

/**
 * Class ApiConsumes.
 *
 * @method static ReceivedPix baseUrl(string $baseUrl);
 * @method static ReceivedPix clientId(string $clientId);
 * @method static ReceivedPix clientSecret(string $clientSecret);
 * @method static ReceivedPix certificate(string $certificate);
 * @method static mixed getOauth2Token();
 * @method static ReceivedPix withFilters($filters);
 * @method static array create(string $loc);
 * @method static array getBye2eid(string $e2eid);
 * @method static array refund(string $e2eid, string $refundId);
 * @method static array consultRefund(string $e2eid, string $refundId);
 * @method static array all();
 */
class ReceivedPixFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return ReceivedPix::class;
    }
}
