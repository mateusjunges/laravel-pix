<?php

namespace Junges\Pix\Contracts;

use Illuminate\Contracts\Support\Arrayable;

interface CobPayloadContract extends Arrayable, PayloadContract
{
    public function getTransactionId(): string;

}