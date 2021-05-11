<?php

namespace Junges\Pix\Api\Contracts;

interface ConsumesCobEndpoints extends ConsumesPixApi
{
    public function create(string $transactionId, array $request): array;

    public function createWithoutTransactionId(array $request): array;

    public function updateByTransactionId(string $transactionId, array $request): array;

    public function getByTransactionId(string $transactionId): array;

    public function all(): array;
}