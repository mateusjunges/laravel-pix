<?php

namespace Junges\Pix\Api;

use Illuminate\Support\Facades\Http;
use Junges\Pix\Api\Contracts\PixApiContract;
use Junges\Pix\Support\Endpoints;

class Api implements PixApiContract
{
    private string $baseUrl;
    private string $clientId;
    private string $clientSecret;
    private string $certificate;
    private string $certificatePassword;
    private string $oauthToken;

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

    public function getCertificate()
    {
        return $this->certificatePassword ?? false
                ? [$this->certificate, $this->certificatePassword]
                : $this->certificate;
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

    public function createCob(ApiRequest $request)
    {
        $endpoint = $this->baseUrl . Endpoints::CREATE_COB . $request->getTransactionId();

        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Cache-Control' => 'no-cache',
            'Authorization' => "Bearer {$this->oauthToken}"
        ])->withOptions([
            'cert' => $this->getCertificate()
        ])
            ->put($endpoint, $request->toArray())
            ->json();
    }
}