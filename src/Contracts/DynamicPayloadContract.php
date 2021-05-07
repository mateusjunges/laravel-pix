<?php

namespace Junges\Pix\Contracts;

use Illuminate\Contracts\Support\Arrayable;

interface DynamicPayloadContract extends PayloadContract
{
    public function getTransactionId(): string;

}