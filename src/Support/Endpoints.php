<?php

namespace Junges\Pix\Support;

class Endpoints
{
    const OAUTH_TOKEN = "/oauth/token/";

    const CREATE_COB = "/cob/";
    const GET_COB = self::CREATE_COB;
    const UPDATE_COB = self::CREATE_COB;
    const GET_ALL_COBS = self::CREATE_COB;

    const CREATE_COBV = "/cobv/";
    const GET_COBV = self::CREATE_COBV;
    const GET_ALL_COBV = self::CREATE_COBV;

    const CREATE_LOTE_COBV = "/lotecobv/";
    const UPDATE_LOTE_COBV = self::CREATE_LOTE_COBV;
    const GET_LOTE_COBV = self::CREATE_LOTE_COBV;
    const GET_ALL_LOTE_COBV = self::CREATE_LOTE_COBV;

    const CREATE_WEBHOOK = "/webhook/";
    const GET_WEBHOOK = self::CREATE_WEBHOOK;
    const DELETE_WEBHOOK = self::CREATE_WEBHOOK;
    const GET_WEBHOOKS = self::CREATE_WEBHOOK;

    const RECEIVED_PIX = "/pix/";
    const RECEIVED_PIX_REFUND = "/devolucao/";

    const CREATE_PAYLOAD_LOCATION = "/loc/";
    const GET_PAYLOAD_LOCATION = self::CREATE_PAYLOAD_LOCATION;
    const DETACH_CHARGE_FROM_LOCATION = self::CREATE_PAYLOAD_LOCATION;
    const PAYLOAD_LOCATION_TXID = "/txid/";
}