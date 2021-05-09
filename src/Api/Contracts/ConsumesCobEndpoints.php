<?php

namespace Junges\Pix\Api\Contracts;

use Junges\Pix\Api\ApiRequest;

interface ConsumesCobEndpoints extends ConsumesPixApi
{
    public function create(ApiRequest $request): array;

    public function getByTransactionId(string $transaction_id): array;

    public function all(): array;
}