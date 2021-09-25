<?php

namespace Junges\Pix\Api\Resources\PayloadLocation;

use Illuminate\Http\Client\Response;
use Junges\Pix\Api\Api;
use Junges\Pix\Api\Contracts\ApplyApiFilters;
use Junges\Pix\Api\Contracts\ConsumesPayloadLocationEndpoints;
use Junges\Pix\Api\Contracts\FilterApiRequests;
use Junges\Pix\Exceptions\ValidationException;
use Junges\Pix\Support\Endpoints;
use RuntimeException;

class PayloadLocation extends Api implements ConsumesPayloadLocationEndpoints, FilterApiRequests
{
    private array $filters = [];

    public function withFilters($filters): PayloadLocation
    {
        if (!is_array($filters) && !$filters instanceof ApplyApiFilters) {
            throw new RuntimeException("Filters should be an instance of 'FilterApiRequests' or an array.");
        }

        $this->filters = $filters instanceof ApplyApiFilters
            ? $filters->toArray()
            : $filters;

        return $this;
    }

    public function create(string $loc): Response
    {
        $endpoint = $this->getEndpoint(
            $this->baseUrl
            .$this->resolveEndpoint(Endpoints::CREATE_PAYLOAD_LOCATION)
        );

        return $this->request()->post($endpoint, ['tipoCob' => $loc]);
    }

    public function getById(string $id): Response
    {
        $endpoint = $this->getEndpoint(
            $this->baseUrl
            .$this->resolveEndpoint(Endpoints::GET_PAYLOAD_LOCATION)
            .$id
        );

        return $this->request()->get($endpoint, $this->filters);
    }

    public function detachChargeFromLocation(string $id): Response
    {
        $endpoint = $this->getEndpoint(
            $this->baseUrl.
            $this->resolveEndpoint(Endpoints::DETACH_CHARGE_FROM_LOCATION)
            .$id
            .$this->resolveEndpoint(Endpoints::PAYLOAD_LOCATION_TXID)
        );

        return $this->request()->delete($endpoint);
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

        $endpoint = $this->getEndpoint($this->baseUrl.$this->resolveEndpoint(Endpoints::GET_PAYLOAD_LOCATION));

        return $this->request()->get($endpoint, $this->filters);
    }
}
