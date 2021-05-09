<?php

namespace Junges\Pix\Api\Contracts;

use Junges\Pix\Api\Features\Cob\CobRequest;

interface ConsumesCobEndpoints extends ConsumesPixApi
{
    public function create(CobRequest $request): array;

    public function getByTransactionId(string $transaction_id): array;

    public function all(): array;
}