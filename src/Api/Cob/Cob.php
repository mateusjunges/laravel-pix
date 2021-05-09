<?php

namespace Junges\Pix\Api\Cob;

use Illuminate\Support\Facades\Http;
use Junges\Pix\Api\Api;
use Junges\Pix\Api\ApiRequest;
use Junges\Pix\Api\Contracts\ConsumesCobEndpoints;
use Junges\Pix\Support\Endpoints;

class Cob extends Api implements ConsumesCobEndpoints
{
    public function create(ApiRequest $request): array
    {
        $endpoint = $this->baseUrl . Endpoints::CREATE_COB . $request->getTransactionId();

        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Cache-Control' => 'no-cache',
        ])->withOptions([
            'cert' => $this->getCertificate()
        ])
            ->withToken($this->oauthToken)
            ->put($endpoint, $request->toArray())
            ->json();
    }

    public function getByTransactionId(string $transaction_id): array
    {
        $endpoint = $this->baseUrl . Endpoints::GET_COB . $transaction_id;

        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Cache-Control' => 'no-cache',
        ])->withOptions([
            'cert' => $this->getCertificate()
        ])
            ->withToken($this->oauthToken)
            ->get($endpoint)
            ->json();
    }

    public function all(): array
    {
        $endpoint = $this->baseUrl . Endpoints::GET_ALL_COBS;

        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Cache-Control' => 'no-cache',
        ])->withOptions([
            'cert' => $this->getCertificate()
        ])
            ->withToken($this->oauthToken)
            ->get($endpoint, $this->getFilters($this->filters ?? []))
            ->json();
    }
}