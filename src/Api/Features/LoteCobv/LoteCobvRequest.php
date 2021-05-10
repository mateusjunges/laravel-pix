<?php

namespace Junges\Pix\Api\Features\LoteCobv;

use Illuminate\Support\Collection;
use Junges\Pix\Api\Contracts\GeneratesLoteCobvRequests;
use Junges\Pix\Api\Features\Cobv\CobvRequest;

class LoteCobvRequest implements GeneratesLoteCobvRequests
{
    private string $description;
    private Collection $cobvs;
    private string $batchId;

    public function batchId(string $id): LoteCobvRequest
    {
        $this->batchId = $id;

        return $this;
    }

    public function description(string $description): LoteCobvRequest
    {
        $this->description = $description;

        return $this;
    }

    public function addCobv(CobvRequest $cobv): LoteCobvRequest
    {
        $this->cobvs->add($cobv->toArray());

        return $this;
    }

    public function toArray(): array
    {
        return [
            "descricao" => $this->description,
            "cobvs" => $this->cobvs->toArray()
        ];
    }

    public function getBatchId(): string
    {
        return $this->batchId;
    }
}