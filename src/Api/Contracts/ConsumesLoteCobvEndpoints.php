<?php

namespace Junges\Pix\Api\Contracts;

interface ConsumesLoteCobvEndpoints extends ConsumesPixApi
{
    public function createBatch(string $batchId, array $request);

    public function updateBatch(string $batchId, array $request);

    public function getByBatchId(string $batchId);

    public function all();
}
