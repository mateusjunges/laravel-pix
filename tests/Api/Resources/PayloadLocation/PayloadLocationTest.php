<?php

namespace Junges\Pix\Tests\Api\Resources\PayloadLocation;

use Junges\Pix\Exceptions\ValidationException;
use Junges\Pix\Pix;
use Junges\Pix\Tests\TestCase;

class PayloadLocationTest extends TestCase
{
    public function test_it_throws_validation_exception_if_filters_are_not_set()
    {
        $this->expectException(ValidationException::class);

        Pix::payloadLocation()->all()->json();
    }
}