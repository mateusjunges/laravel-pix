<?php

namespace Junges\Pix\Api\Contracts;

use Illuminate\Contracts\Support\Arrayable;

interface GeneratesCobRequests extends Arrayable
{
    public function getTransactionId(): string;
}