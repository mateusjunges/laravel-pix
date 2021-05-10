<?php

namespace Junges\Pix\Api\Contracts;

use Junges\Pix\Api\Features\Cobv\CobvRequest;
use Junges\Pix\Api\Features\Cobv\UpdateCobvRequest;

interface ConsumesCobvEndpoints
{
    public function all(): array;

    public function create(CobvRequest $request): array;

    public function update(UpdateCobvRequest $request): array;

    public function getByTransactionId(string $transactionId): array;
}