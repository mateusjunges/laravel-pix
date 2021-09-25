<?php

namespace Junges\Pix\Api;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Junges\Pix\Api\Contracts\ConsumesPixApi;
use Junges\Pix\Contracts\CanResolveEndpoints;
use Junges\Pix\Providers\PixServiceProvider;
use Junges\Pix\Psp;

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
    protected Psp $psp;
    protected CanResolveEndpoints $endpointsResolver;

    public function __construct()
    {
        $this->psp = new Psp();

        $this->oauthToken($this->psp->getPspOauthBearerToken())
            ->certificate($this->psp->getPspSSLCertificate())
            ->baseUrl($this->psp->getPspBaseUrl())
            ->clientId($this->psp->getPspClientId())
            ->clientSecret($this->psp->getPspClientSecret());
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

    public function usingPsp(string $psp): Api
    {
        $this->psp->currentPsp($psp);

        $this->oauthToken($this->psp->getPspOauthBearerToken())
            ->certificate($this->psp->getPspSSLCertificate())
            ->baseUrl($this->psp->getPspBaseUrl())
            ->clientId($this->psp->getPspClientId())
            ->clientSecret($this->psp->getPspClientSecret());

        return $this;
    }

    public function usingDefaultPsp(): Api
    {
        $this->psp->currentPsp(Psp::getDefaultPsp());

        return $this;
    }

    public function getPsp(): Psp
    {
        return $this->psp;
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
        $authentication_class = $this->getPsp()->getAuthenticationClass();

        return app($authentication_class, [
            'clientId'                => $this->clientId,
            'clientSecret'            => $this->clientSecret,
            'certificate'             => $this->certificate,
            'certificatePassword'     => $this->certificatePassword,
            'currentPspOauthEndpoint' => $this->psp->getOauthTokenUrl(),
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

    protected function resolveEndpoint(string $endpoint): string
    {
        return $this->getPsp()->getEndpointsResolver()->getEndpoint($endpoint);
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
