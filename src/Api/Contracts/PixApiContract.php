<?php

namespace Junges\Pix\Api\Contracts;

use Junges\Pix\Api;

interface PixApiContract
{
    public function baseUrl(string $baseUrl): self;

    public function clientId(string $clientId): self;

    public function clientSecret(string $clientSecret): self;

    public function certificate(string $certificate): self;

    public function getOauth2Token();
}