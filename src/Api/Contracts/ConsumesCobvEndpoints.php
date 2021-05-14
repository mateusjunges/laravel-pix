<?php

namespace Junges\Pix\Api\Contracts;

interface ConsumesCobvEndpoints
{
    public function all();

    public function createWithTransactionId(string $transactionId, array $request);

    public function updateWithTransactionId(string $transactionId, array $request);

    public function getByTransactionId(string $transactionId);
}
