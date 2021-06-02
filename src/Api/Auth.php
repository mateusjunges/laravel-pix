<?php

namespace Junges\Pix\Api;

use Illuminate\Support\Facades\Http;
use Junges\Pix\Api\Contracts\AuthenticatesPSPs;
use Junges\Pix\Providers\PixServiceProvider;

class Auth implements AuthenticatesPSPs
{
    protected string $clientId;
    protected string $clientSecret;
    protected string $certificate;
    protected string $currentPspOauthEndpoint;
    protected ?string $certificatePassword;

    public function __construct(
        string $clientId,
        string $clientSecret,
        string $certificate,
        string $currentPspOauthEndpoint,
        ?string $certificatePassword
    ) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->certificate = $certificate;
        $this->currentPspOauthEndpoint = $currentPspOauthEndpoint;
        $this->certificatePassword = $certificatePassword;
    }

    public function getToken(string $scopes = null)
    {
        $client = Http::withHeaders([
            'Content-Type'  => 'application/json',
            'Authorization' => 'Basic '.base64_encode("{$this->clientId}:{$this->clientSecret}"),
        ])->withOptions([
            'auth' => [$this->clientId, $this->clientSecret],
        ]);

        if ($this->shouldVerifySslCertificate()) {
            $client->withOptions([
                'verify' => $this->certificate,
                'cert'   => $this->getCertificate(),
            ]);
        }

        return $client->post($this->getOauthEndpoint(), [
            'grant_type' => 'client_credentials',
            'scope'      => $scopes ?? '',
        ]);
    }

    protected function getCertificate()
    {
        return $this->certificatePassword ?? false
                ? [$this->certificate, $this->certificatePassword]
                : $this->certificate;
    }

    private function shouldVerifySslCertificate(): bool
    {
        return PixServiceProvider::$verifySslCertificate;
    }

    public function getOauthEndpoint(): string
    {
        return $this->currentPspOauthEndpoint;
    }
}
