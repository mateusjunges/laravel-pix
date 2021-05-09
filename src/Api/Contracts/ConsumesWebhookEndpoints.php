<?php

namespace Junges\Pix\Api\Contracts;

interface ConsumesWebhookEndpoints extends ConsumesPixApi
{
    public function create(string $pixKey): array;

    public function getViaPixKey(string $pixKey): array;

    public function delete(string $pixKey): array;

    public function all(): array;
}