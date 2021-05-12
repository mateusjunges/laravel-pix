<?php

namespace Junges\Pix\Api;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Junges\Pix\Api\Contracts\ConsumesPixApi;
use Junges\Pix\Support\Endpoints;

class Api implements ConsumesPixApi
{
    protected string $baseUrl;
    protected string $clientId;
    protected string $clientSecret;
    protected string $certificate;
    protected string $certificatePassword;
    protected string $oauthToken;
    protected array $additionalParams = [];
    private bool $verifySslCertificate = false;

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

    public function validatingSslCertificate(bool $validate = true): Api
    {
        $this->verifySslCertificate = $validate;

        return $this;
    }

    public function withoutVerifyingSslCertificate(): Api
    {
        return $this->validatingSslCertificate(false);
    }

    public function oauthToken(string $oauthToken): Api
    {
        $this->oauthToken = $oauthToken;

        return $this;
    }

    protected function request(): PendingRequest
    {
        $client =  Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Cache-Control' => 'no-cache',
        ]);

        if ($this->shouldVerifySslCertificate()) {
            $client->withOptions([
                'cert' => $this->getCertificate()
            ]);
        }

        $client->withToken($this->oauthToken);

        return $client;
    }

    public function getOauth2Token()
    {
        $client = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->withOptions([
            'auth' => [$this->clientId, $this->clientSecret]
        ]);

        if ($this->shouldVerifySslCertificate()) {
            $client->withOptions([
                'verify' => $this->certificate,
                'cert' => $this->getCertificate()
            ]);
        }

        return $client->post($this->getOauthEndpoint(), [
            'grant_type' => 'client_credentials'
        ])->json();
    }

    public function withAdditionalParams(array $params): Api
    {
        $this->additionalParams = $params;

        return $this;
    }

    public function getEndpoint(string $endpoint): string
    {
        return $endpoint . "?" . http_build_query($this->additionalParams);
    }

    protected function getCertificate()
    {
        return $this->certificatePassword ?? false
                ? [$this->certificate, $this->certificatePassword]
                : $this->certificate;
    }

    private function shouldVerifySslCertificate(): bool
    {
        return $this->verifySslCertificate;
    }

    private function getOauthEndpoint()
    {
        if ($tokenEndpoint = config('laravel-pix.psp.oauth_token_url', false)) {
            return $tokenEndpoint;
        }
        return $this->baseUrl . Endpoints::OAUTH_TOKEN;
    }
}