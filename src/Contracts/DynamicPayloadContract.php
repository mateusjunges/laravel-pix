<?php

namespace Junges\Pix\Contracts;

use Illuminate\Contracts\Support\Arrayable;

interface DynamicPayloadContract extends Arrayable, PayloadContract
{
    public function getTransactionId(): string;

}