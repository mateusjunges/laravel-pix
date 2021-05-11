<?php

namespace Junges\Pix\Api\Contracts;

interface ConsumesLoteCobvEndpoints extends ConsumesPixApi
{
    public function create(GeneratesLoteCobvRequests $request): array;

    public function update(GeneratesLoteCobvRequests $request): array;

    public function getByBatchId(string $batchId): array;

    public function all(): array;
}