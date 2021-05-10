<?php

namespace Junges\Pix\Api\Features\LoteCobv;

use Illuminate\Support\Collection;

class UpdateLoteCobvRequest
{
    private Collection $cobvs;
    private string $batchId;

    public function addCobv(UpdateCobvItem $item)
    {
        $this->cobvs->add($item->toArray());

        return $this;
    }

    public function batchId(string $batchId): UpdateLoteCobvRequest
    {
        $this->batchId = $batchId;

        return $this;
    }

    public function getBatchId(): string
    {
        return $this->batchId;
    }

    public function toArray(): array
    {
        return [
            "cobvs" => $this->cobvs->toArray()
        ];
    }
}