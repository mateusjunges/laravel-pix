<?php

namespace Junges\Pix\Api\Contracts;

interface GeneratesCobvRequests extends ApiRequest
{
    public function getTransactionId(): string;
}