<?php

namespace Junges\Pix\Api\Features\ReceivedPix;

use Junges\Pix\Api\Api;
use Junges\Pix\Api\Contracts\ApplyApiFilters;
use Junges\Pix\Api\Contracts\ConsumesReceivedPixEndpoints;
use Junges\Pix\Api\Contracts\FilterApiRequests;
use Junges\Pix\Support\Endpoints;
use RuntimeException;

class ReceivedPix extends Api implements FilterApiRequests, ConsumesReceivedPixEndpoints
{
    private array $filters;

    public function getBye2eid(string $e2eid): array
    {
        $endpoint = $this->baseUrl . Endpoints::RECEIVED_PIX . $e2eid;

        return $this->request()->get($endpoint)->json();
    }

    public function refund(string $e2eid, string $refundId): array
    {
        $endpoint = $this->baseUrl . Endpoints::RECEIVED_PIX . $e2eid . Endpoints::RECEIVED_PIX_REFUND . $refundId;

        return $this->request()->put($endpoint)->json();
    }

    public function consultRefund(string $e2eid, string $refundId): array
    {
        $endpoint = $this->baseUrl . Endpoints::RECEIVED_PIX . $e2eid . Endpoints::RECEIVED_PIX_REFUND . $refundId;

        return $this->request()->get($endpoint)->json();
    }

    public function all(): array
    {
        $endpoint = $this->baseUrl . Endpoints::RECEIVED_PIX;

        return $this->request()
            ->get($endpoint, $this->getFilters($this->filters ?? []))
            ->json();
    }

    public function withFilters($filters): ReceivedPix
    {
        if (! is_array($filters) && ! $filters instanceof ApplyApiFilters) {
            throw new RuntimeException("Filters should be an instance of 'FilterApiRequests' or an array.");
        }

        $this->filters = $filters instanceof ApplyApiFilters
            ? $filters->toArray()
            : $filters;

        return $this;
    }
}