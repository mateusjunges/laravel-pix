<?php

namespace Junges\Pix\Api\Features\Cob;

use Junges\Pix\Api\Api;
use Junges\Pix\Api\Contracts\ApplyApiFilters;
use Junges\Pix\Api\Contracts\ConsumesCobEndpoints;
use Junges\Pix\Api\Contracts\FilterApiRequests;
use Junges\Pix\Support\Endpoints;
use RuntimeException;

class Cob extends Api implements ConsumesCobEndpoints, FilterApiRequests
{
    private array $filters;

    public function withFilters($filters): Cob
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

    public function create(CobRequest $request): array
    {
        $endpoint = $this->baseUrl . Endpoints::CREATE_COB . $request->getTransactionId();

        return $this->request()
            ->put($endpoint, $request->toArray())
            ->json();
    }

    public function createWithoutTransactionId(CobRequest $request): array
    {
        $endpoint = $this->baseUrl . Endpoints::CREATE_COB;

        return $this->request()
            ->post($endpoint, $request->toArray())
            ->json();
    }

    public function getByTransactionId(string $transactionId): array
    {
        $endpoint = $this->baseUrl . Endpoints::GET_COB . $transactionId;

        return $this->request()
            ->get($endpoint)
            ->json();
    }

    public function update(UpdateCobRequest $request): array
    {
        $endpoint = $this->baseUrl . Endpoints::UPDATE_COB . $request->getTransactionId();

        return $this->request()
            ->patch($endpoint, $request->toArray())
            ->json();
    }

    public function all(): array
    {
        $endpoint = $this->baseUrl . Endpoints::GET_ALL_COBS;

        return $this->request()
            ->get($endpoint, $this->getFilters($this->filters ?? []))
            ->json();
    }
}