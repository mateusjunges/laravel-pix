<?php

namespace Junges\Pix\Api\Contracts;

use Junges\Pix\Api\Features\CobV\CobVRequest;
use Junges\Pix\Api\Features\CobV\UpdateCobvRequest;

interface ConsumesCobVEndpoints
{
    public function all(): array;

    public function create(CobVRequest $request): array;

    public function update(UpdateCobvRequest $request): array;

    public function getByTransactionId(string $transactionId): array;
}