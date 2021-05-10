<?php

namespace Junges\Pix\Support;

class Endpoints
{
    const OAUTH_TOKEN = "/oauth/token/";
    const CREATE_COB = "/v2/cob/";
    const GET_COB = self::CREATE_COB;
    const UPDATE_COB = self::CREATE_COB;
    const GET_ALL_COBS = self::CREATE_COB;
    const CREATE_COBV = "/v2/cobv/";
    const GET_COBV = self::CREATE_COBV;
    const GET_ALL_COBV = self::CREATE_COBV;
    const CREATE_WEBHOOK = "/v2/webhook/";
    const GET_WEBHOOK = self::CREATE_WEBHOOK;
    const DELETE_WEBHOOK = self::CREATE_WEBHOOK;
    const GET_WEBHOOKS = self::CREATE_WEBHOOK;
}