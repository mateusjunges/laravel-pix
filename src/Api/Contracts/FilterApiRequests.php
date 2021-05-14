<?php

namespace Junges\Pix\Api\Contracts;

interface FilterApiRequests
{
    public function withFilters($filters): self;
}
