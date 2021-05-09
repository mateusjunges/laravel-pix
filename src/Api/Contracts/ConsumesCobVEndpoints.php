<?php

namespace Junges\Pix\Api\Contracts;

use Junges\Pix\Api\Features\CobV\CobVRequest;

interface ConsumesCobVEndpoints
{
    public function all(): array;

    public function create(CobVRequest $request): array;

    public function getByTransactionId(string $transactionId): array;
}