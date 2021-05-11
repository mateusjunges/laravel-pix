<?php

namespace Junges\Pix\Api\Features\LoteCobv;

use Junges\Pix\Api\Api;
use Junges\Pix\Api\Contracts\ApplyApiFilters;
use Junges\Pix\Api\Contracts\ConsumesLoteCobvEndpoints;
use Junges\Pix\Api\Contracts\FilterApiRequests;
use Junges\Pix\Support\Endpoints;
use RuntimeException;

class LoteCobv extends Api implements ConsumesLoteCobvEndpoints, FilterApiRequests
{
    private array $filters;

    public function withFilters($filters): LoteCobv
    {
        if (! is_array($filters) && ! $filters instanceof ApplyApiFilters) {
            throw new RuntimeException("Filters should be an instance of 'FilterApiRequests' or an array.");
        }

        $this->filters = $filters instanceof ApplyApiFilters
            ? $filters->toArray()
            : $filters;

        return $this;
    }

    public function create(LoteCobvRequest $request): array
    {
        $endpoint = $this->baseUrl . Endpoints::CREATE_LOTE_COBV . $request->getBatchId();

        return $this->request()
            ->put($endpoint, $request->toArray())
            ->json();
    }

    public function update(UpdateLoteCobvRequest $request): array
    {
        $endpoint = $this->baseUrl . Endpoints::UPDATE_LOTE_COBV . $request->getBatchId();

        return $this->request()
            ->patch($endpoint, $request->toArray())
            ->json();
    }

    public function getByBatchId(string $batchId): array
    {
        $endpoint = $this->baseUrl . Endpoints::GET_LOTE_COBV . $batchId;

        return $this->request()->get($endpoint)->json();
    }

    public function all(): array
    {
        $endpoint = $this->baseUrl . Endpoints::GET_ALL_LOTE_COBV;

        return $this->request()
            ->get($endpoint, $this->getFilters($this->filters ?? []))
            ->json();
    }
}