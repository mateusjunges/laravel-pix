<?php

namespace Junges\Pix\Api\Features\CobV;

use Junges\Pix\Api\Api;
use Junges\Pix\Api\Contracts\ApplyApiFilters;
use Junges\Pix\Api\Contracts\ConsumesCobVEndpoints;
use Junges\Pix\Api\Contracts\FilterApiRequests;
use Junges\Pix\Support\Endpoints;

class CobV extends Api implements FilterApiRequests, ConsumesCobVEndpoints
{
    private $filters;

    public function create(CobVRequest $request): array
    {
        $endpoint = $this->baseUrl . Endpoints::CREATE_COBV . "/{$request->getTransactionId()}";

        return $this->request()
            ->put($endpoint, $request->toArray())
            ->json();
    }

    public function getByTransactionId(string $transactionId): array
    {
        $endpoint = $this->baseUrl . Endpoints::GET_COBV . $transactionId;

        return $this->request()
            ->get($endpoint, $this->filters ?? null)
            ->json();
    }

    public function withFilters($filters): CobV
    {
        $this->filters = $filters instanceof ApplyApiFilters
            ? $filters->toArray()
            : $filters;

        return $this;
    }

    public function all(): array
    {
        $endpoint = $this->baseUrl . Endpoints::GET_ALL_COBV;

        return $this->request()
            ->get($endpoint, $this->getFilters($this->filters ?? null))
            ->json();
    }
}