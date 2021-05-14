<?php

namespace Junges\Pix\Api;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Junges\Pix\Api\Contracts\AuthenticatesWithOauth;
use Junges\Pix\Api\Contracts\ConsumesPixApi;
use Junges\Pix\Providers\PixServiceProvider;

class Api implements ConsumesPixApi
{
    protected string $baseUrl;
    protected string $clientId;
    protected string $clientSecret;
    protected string $certificate;
    protected ?string $certificatePassword = null;
    protected ?string $oauthToken;
    protected array $additionalParams = [];
    protected array $additionalOptions = [];

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

    public function oauthToken(?string $oauthToken): Api
    {
        $this->oauthToken = $oauthToken;

        return $this;
    }

    protected function request(): PendingRequest
    {
        $client = Http::withHeaders([
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
            'Cache-Control' => 'no-cache',
        ]);

        if ($this->shouldVerifySslCertificate()) {
            $client->withOptions([
                'cert' => $this->getCertificate(),
            ]);
        }

        $client->withToken($this->oauthToken);

        return $client;
    }

    protected function getCertificate()
    {
        return $this->certificatePassword ?? false
                ? [$this->certificate, $this->certificatePassword]
                : $this->certificate;
    }

    public function getOauth2Token(string $scopes = null)
    {
        return app(AuthenticatesWithOauth::class, [
            'clientId'            => $this->clientId,
            'clientSecret'        => $this->clientSecret,
            'certificate'         => $this->certificate,
            'certificatePassword' => $this->certificatePassword,
        ])->getToken();
    }

    public function withAdditionalParams(array $params): Api
    {
        $this->additionalParams = $params;

        return $this;
    }

    public function withOptions(array $options): Api
    {
        $this->additionalOptions = $options;

        return $this;
    }

    protected function getEndpoint(string $endpoint): string
    {
        return $endpoint.'?'.http_build_query($this->additionalParams);
    }

    private function shouldVerifySslCertificate(): bool
    {
        return PixServiceProvider::$verifySslCertificate;
    }
}
