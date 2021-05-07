<?php

namespace Junges\Pix\Contracts;

use Junges\Pix\DynamicPayload;
use Junges\Pix\Payload;

interface GeneratesQrCodeContract
{
    public function withPayload(Payload $payload);

    public function withDynamicPayload(DynamicPayload $payload);
}