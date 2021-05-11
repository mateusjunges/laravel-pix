<?php

namespace Junges\Pix\Api\Contracts;

interface ConsumesLoteCobvEndpoints extends ConsumesPixApi
{
    public function createBatch(string $batchId, array $request): array;

    public function updateBatch(string $batchId, array $request): array;

    public function getByBatchId(string $batchId): array;

    public function all(): array;
}