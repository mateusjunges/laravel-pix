<?php

namespace Junges\Pix\Api\Contracts;

interface GeneratesCobRequests extends ApiRequest
{
    public function getTransactionId(): string;
}