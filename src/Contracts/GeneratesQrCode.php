<?php

namespace Junges\Pix\Contracts;

interface GeneratesQrCode
{
    public function withPayload(PixPayloadContract $payload);
}
