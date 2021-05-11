<?php

namespace Junges\Pix\Api\Contracts;

interface ConsumesCobvEndpoints
{
    public function all(): array;

    public function create(GeneratesCobvRequests $request): array;

    public function update(GeneratesCobvRequests $request): array;

    public function getByTransactionId(string $transactionId): array;
}