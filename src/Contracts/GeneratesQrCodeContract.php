<?php

namespace Junges\Pix\Contracts;

interface GeneratesQrCodeContract
{
    public function withPayload(PixPayloadContract $payload);

    public function withDynamicPayload(DynamicPayloadContract $payload);
}