<?php

namespace Junges\Pix\Api\Features\Webhook;

use Illuminate\Support\Facades\Http;
use Junges\Pix\Api\Api;
use Junges\Pix\Api\Contracts\ApplyApiFilters;
use Junges\Pix\Api\Contracts\ConsumesWebhookEndpoints;
use Junges\Pix\Api\Contracts\FilterApiRequests;
use Junges\Pix\Support\Endpoints;

class Webhook extends Api implements ConsumesWebhookEndpoints, FilterApiRequests
{
    private string $webhookUrl;
    private $filters;

    public function webhookUrl(string $url): Webhook
    {
        $this->webhookUrl = $url;

        return $this;
    }

    public function withFilters($filters): Webhook
    {
        $this->filters = $filters instanceof ApplyApiFilters
            ? $filters->toArray()
            : $filters;

        return $this;
    }

    public function create(string $pixKey): array
    {
        $endpoint = $this->baseUrl . Endpoints::CREATE_WEBHOOK . $pixKey;

        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Cache-Control' => 'no-cache'
        ])->withOptions([
            'cert' => $this->getCertificate()
        ])
            ->withToken($this->oauthToken)
            ->put($endpoint, [
                'webhookUrl' => $this->webhookUrl
            ])
            ->json();
    }

    public function getViaPixKey(string $pixKey): array
    {
        $endpoint = $this->baseUrl . Endpoints::GET_WEBHOOK . $pixKey;

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

    public function delete(string $pixKey): array
    {
        $endpoint = $this->baseUrl . Endpoints::DELETE_WEBHOOK . $pixKey;

        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Cache-Control' => 'no-cache',
        ])->withOptions([
            'cert' => $this->getCertificate()
        ])
            ->withToken($this->oauthToken)
            ->delete($endpoint)
            ->json();
    }

    public function all(): array
    {
        $endpoint = $this->baseUrl . Endpoints::GET_WEBHOOKS;
        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Cache-Control' => 'no-cache',
        ])->withOptions([
            'cert' => $this->getCertificate()
        ])
            ->withToken($this->oauthToken)
            ->get($endpoint, $this->getFilters($this->filters) ?? null)
            ->json();
    }
}