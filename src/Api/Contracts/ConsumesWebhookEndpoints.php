<?php

namespace Junges\Pix\Api\Contracts;

interface ConsumesWebhookEndpoints extends ConsumesPixApi
{
    public function create(string $pixKey);

    public function getByPixKey(string $pixKey);

    public function delete(string $pixKey);

    public function all();
}