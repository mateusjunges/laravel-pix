<?php

namespace Junges\Pix\Api\Resources\ReceivedPix;

use Illuminate\Http\Client\Response;
use Junges\Pix\Api\Api;
use Junges\Pix\Api\Contracts\ApplyApiFilters;
use Junges\Pix\Api\Contracts\ConsumesReceivedPixEndpoints;
use Junges\Pix\Api\Contracts\FilterApiRequests;
use Junges\Pix\Events\ReceivedPix\RefundRequestedEvent;
use Junges\Pix\Exceptions\ValidationException;
use Junges\Pix\Support\Endpoints;
use RuntimeException;

class ReceivedPix extends Api implements FilterApiRequests, ConsumesReceivedPixEndpoints
{
    private array $filters = [];

    public function withFilters($filters): ReceivedPix
    {
        if (!is_array($filters) && !$filters instanceof ApplyApiFilters) {
            throw new RuntimeException("Filters should be an instance of 'FilterApiRequests' or an array.");
        }

        $this->filters = $filters instanceof ApplyApiFilters
            ? $filters->toArray()
            : $filters;

        return $this;
    }

    public function getBye2eid(string $e2eid): Response
    {
        $endpoint = $this->getEndpoint($this->baseUrl.$this->resolveEndpoint(Endpoints::RECEIVED_PIX).$e2eid);

        return $this->request()->get($endpoint);
    }

    public function refund(string $e2eid, string $refundId): Response
    {
        $endpoint = $this->getEndpoint(
            $this->baseUrl
            .$this->resolveEndpoint(Endpoints::RECEIVED_PIX)
            .$e2eid
            .$this->resolveEndpoint(Endpoints::RECEIVED_PIX_REFUND)
            .$refundId
        );

        $refund = $this->request()->put($endpoint);

        event(new RefundRequestedEvent($refund->json(), $e2eid, $refundId));

        return $refund;
    }

    public function consultRefund(string $e2eid, string $refundId): Response
    {
        $endpoint = $this->getEndpoint(
            $this->baseUrl
            .$this->resolveEndpoint(Endpoints::RECEIVED_PIX)
            .$e2eid
            .$this->resolveEndpoint(Endpoints::RECEIVED_PIX_REFUND)
            .$refundId
        );

        return $this->request()->get($endpoint);
    }

    /**
     * @throws \Throwable
     */
    public function all(): Response
    {
        throw_if(
            empty($this->filters),
            ValidationException::filtersAreRequired()
        );

        $endpoint = $this->getEndpoint($this->baseUrl.$this->resolveEndpoint(Endpoints::RECEIVED_PIX));

        return $this->request()->get($endpoint, $this->filters);
    }
}
