<?php

namespace Junges\Pix\Support;

use Exception;
use Junges\Pix\Contracts\CanResolveEndpoints;

class Endpoints implements CanResolveEndpoints
{
    const OAUTH_TOKEN = 'oauth_token';
    const CREATE_COB = 'create_cob';
    const GET_COB = 'get_cob';
    const UPDATE_COB = 'update_cob';
    const GET_ALL_COBS = 'get_all_cobs';

    const CREATE_COBV = 'create_cobv';
    const GET_COBV = 'get_cobv';
    const GET_ALL_COBV = 'get_all_cobv';

    const CREATE_LOTE_COBV = 'create_lote_cobv';
    const UPDATE_LOTE_COBV = 'update_lote_cobv';
    const GET_LOTE_COBV = 'get_lote_cobv';
    const GET_ALL_LOTE_COBV = 'get_all_lote_cobv';

    const CREATE_WEBHOOK = 'create_webhook';
    const GET_WEBHOOK = 'get_webhook';
    const DELETE_WEBHOOK = 'delete_webhook';
    const GET_WEBHOOKS = 'get_webhooks';

    const RECEIVED_PIX = 'received_pix';
    const RECEIVED_PIX_REFUND = 'received_pix_refund';

    const CREATE_PAYLOAD_LOCATION = 'create_payload_location';
    const GET_PAYLOAD_LOCATION = 'get_payload_location';
    const DETACH_CHARGE_FROM_LOCATION = 'detach_charge_from_location';
    const PAYLOAD_LOCATION_TXID = 'payload_location_txid';

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

        self::RECEIVED_PIX        => '/pix/',
        self::RECEIVED_PIX_REFUND => '/devolucao/',

        self::CREATE_PAYLOAD_LOCATION     => '/loc/',
        self::GET_PAYLOAD_LOCATION        => '/loc/',
        self::DETACH_CHARGE_FROM_LOCATION => '/loc/',
        self::PAYLOAD_LOCATION_TXID       => '/loc/',
    ];

    public function setEndpoint(string $key, string $value): void
    {
        $this->endpoints[$key] = $value;
    }

    /**
     * @param string $key
     *
     * @throws Exception
     *
     * @return string
     */
    public function getEndpoint(string $key): string
    {
        if (!$endpoint = $this->endpoints[$key]) {
            throw new Exception("Endpoint does not exist: '{$key}'");
        }

        return $endpoint;
    }
}
