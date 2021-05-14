<?php

namespace Junges\Pix\Api\Contracts;

interface ConsumesPayloadLocationEndpoints extends ConsumesPixApi
{
    public function create(string $loc);

    public function all();

    public function getById(string $id);

    public function detachChargeFromLocation(string $id);
}
