<?php

namespace Junges\Pix\Api\Contracts;

interface ConsumesCobvEndpoints
{
    public function all(): array;

    public function createWithTransactionId(string $transactionId, array $request): array;

    public function updateWithTransactionId(string $transactionId, array $request): array;

    public function getByTransactionId(string $transactionId): array;
}