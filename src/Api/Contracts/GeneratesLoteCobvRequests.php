<?php

namespace Junges\Pix\Api\Contracts;

use Illuminate\Contracts\Support\Arrayable;

interface GeneratesLoteCobvRequests extends Arrayable
{
    public function addCobv(GeneratesCobvRequests $cobv): self;

    public function getBatchId(): string;
}