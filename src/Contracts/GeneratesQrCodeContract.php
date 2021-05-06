<?php

namespace Junges\Pix\Contracts;

use Junges\Pix\Payload;

interface GeneratesQrCodeContract
{
    public function generateForPayload(Payload $payload);
}