<?php

namespace Junges\Pix\Api\Contracts;

interface ConsumesPayloadLocationEndpoints extends ConsumesPixApi
{
    public function create(string $loc): array;

    public function all(): array;

    public function getById(string $id): array;

    public function detachChargeFromLocation(string $id): array;
}