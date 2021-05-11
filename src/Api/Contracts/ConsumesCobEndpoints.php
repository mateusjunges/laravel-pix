<?php

namespace Junges\Pix\Api\Contracts;

interface ConsumesCobEndpoints extends ConsumesPixApi
{
    public function create(GeneratesCobRequests $request): array;

    public function createWithoutTransactionId(GeneratesCobRequests $request): array;

    public function update(GeneratesCobRequests $request): array;

    public function getByTransactionId(string $transactionId): array;

    public function all(): array;
}