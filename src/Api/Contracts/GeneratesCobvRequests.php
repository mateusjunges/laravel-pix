<?php

namespace Junges\Pix\Api\Contracts;

use Illuminate\Contracts\Support\Arrayable;

interface GeneratesCobvRequests extends Arrayable
{
    public function getTransactionId(): string;
}