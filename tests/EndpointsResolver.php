<?php

namespace Junges\Pix\Tests;

use Junges\Pix\Support\Endpoints;

class EndpointsResolver extends Endpoints
{
    public array $endpoints = [
        self::OAUTH_TOKEN  => '/oauth/token',
        self::CREATE_COB   => '/cob/',
        self::GET_COB      => '/cob/',
        self::UPDATE_COB   => '/cob/',
        self::GET_ALL_COBS => '/cob/',

        self::CREATE_COBV  => '/cobv/',
        self::GET_COBV     => '/cobv/',
        self::GET_ALL_COBV => '/cobv/',

        self::CREATE_LOTE_COBV  => '/lotecobv/',
        self::UPDATE_LOTE_COBV  => '/lotecobv/',
        self::GET_LOTE_COBV     => '/lotecobv/',
        self::GET_ALL_LOTE_COBV => '/lotecobv/',

        self::CREATE_WEBHOOK => '/webhook/',
        self::GET_WEBHOOK    => '/webhook/',
        self::DELETE_WEBHOOK => '/webhook/',
        self::GET_WEBHOOKS   => '/webhooks/',

        self::RECEIVED_PIX        => '/test-replace-url/',
        self::RECEIVED_PIX_REFUND => '/devolucao/',

        self::CREATE_PAYLOAD_LOCATION     => '/loc/',
        self::GET_PAYLOAD_LOCATION        => '/loc/',
        self::DETACH_CHARGE_FROM_LOCATION => '/loc/',
        self::PAYLOAD_LOCATION_TXID       => '/loc/',
    ];
}
