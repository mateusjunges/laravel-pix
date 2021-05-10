<?php

namespace Junges\Pix\Api\Contracts;

use Junges\Pix\Api\Features\Cob\CobRequest;
use Junges\Pix\Api\Features\Cob\UpdateCobRequest;

interface ConsumesCobEndpoints extends ConsumesPixApi
{
    public function create(CobRequest $request): array;

    public function createWithoutTransactionId(CobRequest $request): array;

    public function update(UpdateCobRequest $request): array;

    public function getByTransactionId(string $transactionId): array;

    public function all(): array;
}