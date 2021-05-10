<?php

namespace Junges\Pix\Api\Features\Webhook;

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

        return $this->request()
            ->put($endpoint, ['webhookUrl' => $this->webhookUrl])
            ->json();
    }

    public function getByPixKey(string $pixKey): array
    {
        $endpoint = $this->baseUrl . Endpoints::GET_WEBHOOK . $pixKey;

        return $this->request()
            ->get($endpoint)
            ->json();
    }

    public function delete(string $pixKey): array
    {
        $endpoint = $this->baseUrl . Endpoints::DELETE_WEBHOOK . $pixKey;

        return $this->request()
            ->delete($endpoint)
            ->json();
    }

    public function all(): array
    {
        $endpoint = $this->baseUrl . Endpoints::GET_WEBHOOKS;

        return $this->request()
            ->get($endpoint, $this->getFilters($this->filters) ?? null)
            ->json();
    }
}