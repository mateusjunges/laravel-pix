<?php

namespace Junges\Pix\Api\Contracts;

interface ConsumesCobEndpoints extends ConsumesPixApi
{
    public function create(string $transactionId, array $request);

    public function createWithoutTransactionId(array $request);

    public function updateByTransactionId(string $transactionId, array $request);

    public function getByTransactionId(string $transactionId);

    public function all();
}
