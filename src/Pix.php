<?php

namespace Junges\Pix;

use Junges\Pix\Api\Api;
use Junges\Pix\Api\Resources\Cob\Cob;
use Junges\Pix\Api\Resources\Cobv\Cobv;
use Junges\Pix\Api\Resources\LoteCobv\LoteCobv;
use Junges\Pix\Api\Resources\PayloadLocation\PayloadLocation;
use Junges\Pix\Api\Resources\ReceivedPix\ReceivedPix;
use Junges\Pix\Api\Resources\Webhook\Webhook;
use Junges\Pix\Contracts\GeneratesQrCode;

class Pix
{
    const PAYLOAD_FORMAT_INDICATOR = '00';
    const POINT_OF_INITIATION_METHOD = '01';
    const MERCHANT_ACCOUNT_INFORMATION = '26';
    const MERCHANT_ACCOUNT_INFORMATION_URL = '25';
    const MERCHANT_ACCOUNT_INFORMATION_GUI = '00';
    const MERCHANT_ACCOUNT_INFORMATION_KEY = '01';
    const MERCHANT_ACCOUNT_INFORMATION_DESCRIPTION = '02';
    const MERCHANT_CATEGORY_CODE = '52';
    const TRANSACTION_CURRENCY = '53';
    const TRANSACTION_AMOUNT = '54';
    const COUNTRY_CODE = '58';
    const MERCHANT_NAME = '59';
    const MERCHANT_CITY = '60';
    const ADDITIONAL_DATA_FIELD_TEMPLATE = '62';
    const ADDITIONAL_DATA_FIELD_TEMPLATE_TXID = '05';
    const CRC16 = '63';
    const CRC16_LENGTH = '04';

    const MAX_DESCRIPTION_LENGTH = 40;
    const MAX_MERCHANT_NAME_LENGTH = 25;
    const MAX_MERCHANT_CITY_LENGTH = 15;
    const MAX_TRANSACTION_ID_LENGTH = 25;
    const MAX_AMOUNT_LENGTH = 13;
    const MIN_TRANSACTION_ID_LENGTH = 26;

    const RANDOM_KEY_TYPE = 'random';
    const CPF_KEY_TYPE = 'cpf';
    const CNPJ_KEY_TYPE = 'cnpj';
    const PHONE_NUMBER_KEY_TYPE = 'phone';
    const EMAIL_KEY_TYPE = 'email';

    const KEY_TYPES = [
        Pix::RANDOM_KEY_TYPE,
        Pix::CPF_KEY_TYPE,
        Pix::CNPJ_KEY_TYPE,
        Pix::PHONE_NUMBER_KEY_TYPE,
        Pix::EMAIL_KEY_TYPE,
    ];

    public static function createQrCode(Payload $payload)
    {
        return app(GeneratesQrCode::class)->withPayload($payload);
    }

    public static function createDynamicQrCode(DynamicPayload $payload)
    {
        return app(GeneratesQrCode::class)->withDynamicPayload($payload);
    }

    /**
     * This method allows you to use only OAuth endpoints.
     *
     * @return Api
     */
    public static function api(): Api
    {
        return new Api();
    }

    /**
     * Manage instant charges.
     *
     * @return Cob
     */
    public static function cob(): Cob
    {
        return new Cob();
    }

    /**
     * Manage charges with a due date.
     *
     * @return Cobv
     */
    public static function cobv(): Cobv
    {
        return new Cobv();
    }

    /**
     * Manage batch of charges with due date.
     *
     * @return LoteCobv
     */
    public static function loteCobv(): LoteCobv
    {
        return new LoteCobv();
    }

    /**
     * Manage pix key webhooks.
     *
     * @return Webhook
     */
    public static function webhook(): Webhook
    {
        return new Webhook();
    }

    /**
     * Manage location configuration to use with payloads.
     *
     * @return PayloadLocation
     */
    public static function payloadLocation(): PayloadLocation
    {
        return new PayloadLocation();
    }

    /**
     * Manage received pix.
     *
     * @return ReceivedPix
     */
    public static function receivedPix(): ReceivedPix
    {
        return new ReceivedPix();
    }
}
