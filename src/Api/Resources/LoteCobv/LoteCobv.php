<?php

namespace Junges\Pix\Api\Resources\LoteCobv;

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

    public function getFilters(array $filters): ?array
    {
        return !empty($filters) ? $filters : null;
    }

    public function createBatch(string $batchId, array $request): array
    {
        $endpoint = $this->baseUrl . Endpoints::CREATE_LOTE_COBV . $batchId;

        return $this->request()
            ->put($endpoint, $request)
            ->json();
    }

    public function updateBatch(string $batchId, array $request): array
    {
        $endpoint = $this->baseUrl . Endpoints::UPDATE_LOTE_COBV . $batchId;

        return $this->request()
            ->patch($endpoint, $request)
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