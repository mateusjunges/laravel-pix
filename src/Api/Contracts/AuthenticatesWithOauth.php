<?php

namespace Junges\Pix\Api\Contracts;

interface AuthenticatesWithOauth
{
    public function getToken(string $scopes = null);

    public function getOauthEndpoint(): string;
}