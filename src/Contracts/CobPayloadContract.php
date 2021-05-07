<?php

namespace Junges\Pix\Contracts;

use Illuminate\Contracts\Support\Arrayable;

interface CobPayloadContract extends Arrayable
{
    public function getTransactionId(): string;


}