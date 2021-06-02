<?php

namespace Junges\Pix\Api\Contracts;

interface AuthenticatesPSPs
{
    public function getToken(string $scopes = null);

    public function getOauthEndpoint(): string;
}
