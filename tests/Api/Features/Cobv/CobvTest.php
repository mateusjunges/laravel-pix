<?php

namespace Junges\Pix\Tests\Api\Features\Cobv;

use Illuminate\Container\Container;
use Junges\Pix\Tests\TestCase;
use Mockery as m;

class CobvTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
        Container::setInstance(null);
    }

    public function test_it_can_create_a_cobv()
    {

    }
}