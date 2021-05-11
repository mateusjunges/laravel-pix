<?php

namespace Junges\Pix\Api\Contracts;

interface GeneratesLoteCobvRequests extends ApiRequest
{
    public function addCobv(GeneratesCobvRequests $cobv): self;

    public function getBatchId(): string;
}