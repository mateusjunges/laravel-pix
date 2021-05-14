<?php

namespace Junges\Pix\Facades;

use Illuminate\Support\Facades\Facade;
use Junges\Pix\Api\Features\Webhook\Webhook;

/**
 * Class ApiConsumes.
 *
 * @method static Webhook baseUrl(string $baseUrl);
 * @method static Webhook clientId(string $clientId);
 * @method static Webhook clientSecret(string $clientSecret);
 * @method static Webhook certificate(string $certificate);
 * @method static mixed getOauth2Token();
 * @method static Webhook withFilters($filters);
 * @method static array create(string $webhookUrl);
 * @method static array delete(string $pixKey);
 * @method static array all();
 * @method static array getByPixKey(string $transactionId);
 */
class WebhookFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return Webhook::class;
    }
}
