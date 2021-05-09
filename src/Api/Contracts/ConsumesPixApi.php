<?php

namespace Junges\Pix\Api\Contracts;

use Junges\Pix\Api\ApiRequest;

interface ConsumesPixApi
{
    public function getOauth2Token();

    public function createCob(ApiRequest $request): array;

    public function getCobInfo(string $transaction_id): array;

    public function getAllCobs(): array;
}