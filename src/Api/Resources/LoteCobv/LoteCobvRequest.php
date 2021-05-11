<?php

namespace Junges\Pix\Api\Resources\LoteCobv;

use Illuminate\Support\Collection;
use Junges\Pix\Api\Contracts\GeneratesCobvRequests;
use Junges\Pix\Api\Contracts\GeneratesLoteCobvRequests;

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

    public function addCobv(GeneratesCobvRequests $cobv): LoteCobvRequest
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