<?php

namespace Junges\Pix\Api;

use Illuminate\Support\Facades\Http;
use Junges\Pix\Api\Contracts\ConsumesPixApi;
use Junges\Pix\Contracts\FilterApiRequests;
use Junges\Pix\Support\Endpoints;
use RuntimeException;

class Api implements ConsumesPixApi
{
    protected string $baseUrl;
    protected string $clientId;
    protected string $clientSecret;
    protected string $certificate;
    protected string $certificatePassword;
    protected string $oauthToken;
    protected $filters;

    public function __construct()
    {
        $this->oauthToken(config('laravel-pix.psp.oauth_bearer_token', ''))
            ->certificate(config('laravel-pix.psp.ssl_certificate', ''))
            ->baseUrl(config('laravel-pix.psp.base_url', ''))
            ->clientId(config('laravel-pix.psp.client_id', ''))
            ->clientSecret(config('laravel-pix.psp.client_secret', ''));
    }

    public function baseUrl(string $baseUrl): Api
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    public function clientId(string $clientId): Api
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function clientSecret(string $clientSecret): Api
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    public function certificate(string $certificate): Api
    {
        $this->certificate = $certificate;

        return $this;
    }

    public function certificatePassword(string $certificatePassword): Api
    {
        $this->certificatePassword = $certificatePassword;

        return $this;
    }

    public function oauthToken(string $oauthToken): Api
    {
        $this->oauthToken = $oauthToken;

        return $this;
    }

    /**
     * @param array|FilterApiRequests $filters
     * @return $this
     */
    public function withFilters($filters): Api
    {
        if (! is_array($filters) && ! $filters instanceof FilterApiRequests) {
            throw new RuntimeException("Filters should be an instance of 'FilterApiRequests' or an array.");
        }

        $this->filters = $filters;

        return $this;
    }

    public function getOauth2Token()
    {
        return Http::withHeaders([
            'Content-Type' => 'application/json'
        ])
            ->withOptions([
                'verify' => $this->certificate,
                'auth' => [$this->clientId, $this->clientSecret],
                'cert' => $this->getCertificate()
            ])
            ->post($this->baseUrl . Endpoints::OAUTH_TOKEN, [
                'grant_type' => 'client_credentials'
            ])->json();
    }

    protected function getCertificate()
    {
        return $this->certificatePassword ?? false
                ? [$this->certificate, $this->certificatePassword]
                : $this->certificate;
    }

    protected function getFilters($filters): array
    {
        return $filters instanceof FilterApiRequests
            ? $filters->toArray()
            : $filters;
    }
}