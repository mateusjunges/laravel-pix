<?php

namespace Junges\Pix;

use Junges\Pix\Contracts\GeneratesQrCodeContract;
use Junges\Pix\Events\QrCodeCreatedEvent;

class Pix
{
    const PAYLOAD_FORMAT_INDICATOR = '00';
    const POINT_OF_INITIATION_METHOD = '01';
    const MERCHANT_ACCOUNT_INFORMATION = '26';
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
        Pix::EMAIL_KEY_TYPE
    ];

    public static function createQrCode(Payload $payload)
    {
        $qr_code = app(GeneratesQrCodeContract::class)->generateForPayload($payload);

        event(new QrCodeCreatedEvent($payload->getPixKey()));


    }
}