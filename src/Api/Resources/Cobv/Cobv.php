<?php

namespace Junges\Pix\Api\Resources\Cobv;

use Junges\Pix\Api\Api;
use Junges\Pix\Api\Contracts\ApplyApiFilters;
use Junges\Pix\Api\Contracts\ConsumesCobvEndpoints;
use Junges\Pix\Api\Contracts\FilterApiRequests;
use Junges\Pix\Api\Contracts\GeneratesCobvRequests;
use Junges\Pix\Support\Endpoints;
use RuntimeException;

class Cobv extends Api implements FilterApiRequests, ConsumesCobvEndpoints
{
    private array $filters;

    public function create(GeneratesCobvRequests $request): array
    {
        $endpoint = $this->baseUrl . Endpoints::CREATE_COBV . $request->getTransactionId();

        return $this->request()
            ->put($endpoint, $request->toArray())
            ->json();
    }

    public function update(GeneratesCobvRequests $request): array
    {
        $endpoint = $this->baseUrl . Endpoints::CREATE_COBV . $request->getTransactionId();

        return $this->request()
            ->patch($endpoint, $request->toArray())
            ->json();
    }

    public function getByTransactionId(string $transactionId): array
    {
        $endpoint = $this->baseUrl . Endpoints::GET_COBV . $transactionId;

        return $this->request()
            ->get($endpoint, $this->filters ?? null)
            ->json();
    }

    public function withFilters($filters): Cobv
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

    public function all(): array
    {
        $endpoint = $this->baseUrl . Endpoints::GET_ALL_COBV;

        return $this->request()
            ->get($endpoint, $this->getFilters($this->filters ?? []))
            ->json();
    }
}