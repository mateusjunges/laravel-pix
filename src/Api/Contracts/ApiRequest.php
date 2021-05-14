<?php

namespace Junges\Pix\Api\Contracts;

use Illuminate\Contracts\Support\Arrayable;

interface ApiRequest extends Arrayable
{
    public function requiredFields(): array;

    public function validate(): void;
}
