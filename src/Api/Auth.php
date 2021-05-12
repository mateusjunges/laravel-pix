<?php

namespace Junges\Pix\Api;

use Illuminate\Support\Facades\Http;
use Junges\Pix\Api\Contracts\AuthenticatesWithOauth;

class Auth implements AuthenticatesWithOauth
{
    private string $clientId;
    private string $clientSecret;
    private string $certificate;
    private ?string $certificatePassword;

    public function __construct(
        string $clientId,
        string $clientSecret,
        string $certificate,
        ?string $certificatePassword
    )
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->certificate = $certificate;
        $this->certificatePassword = $certificatePassword;
    }

    public function getToken(string $scopes = null)
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
            'grant_type' => 'client_credentials',
            'scope' => $scopes ?? "",
        ])->json();
    }

    protected function getCertificate()
    {
        return $this->certificatePassword ?? false
                ? [$this->certificate, $this->certificatePassword]
                : $this->certificate;
    }

    private function shouldVerifySslCertificate(): bool
    {
        return Api::$verifySslCertificate;
    }

    public function getOauthEndpoint(): string
    {
        return config('laravel-pix.psp.oauth_token_url');
    }
}