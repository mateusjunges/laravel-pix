<?php

namespace Junges\Pix\Api\Contracts;

use Junges\Pix\Api\Features\LoteCobv\LoteCobvRequest;
use Junges\Pix\Api\Features\LoteCobv\UpdateLoteCobvRequest;

interface ConsumesLoteCobvEndpoints extends ConsumesPixApi
{
    public function create(LoteCobvRequest $request): array;

    public function update(UpdateLoteCobvRequest $request): array;

    public function getByBatchId(string $batchId): array;

    public function all(): array;
}