<?php

namespace Junges\Pix\Tests;

use Junges\Pix\Exceptions\Psp\InvalidPspException;
use Junges\Pix\LaravelPix;
use Junges\Pix\Psp;

class PspTest extends TestCase
{
    public function test_it_can_get_the_available_psps()
    {
        config(['laravel-pix.psp' => ['test' => [], 'test-2' => ['dummy_key' => 'dummy_value'], 'dummy_psp' => []]]);

        $expected = ['test', 'test-2', 'dummy_psp'];

        $this->assertEquals($expected, Psp::availablePsps());
    }

    public function test_it_can_switch_default_psp_in_runtime()
    {
        config(['laravel-pix.psp' => ['default' => [], 'dummy-psp' => []]]);

        $this->assertEquals('default', Psp::getConfig()->getCurrentPsp());

        Psp::defaultPsp('dummy-psp');

        $this->assertEquals('dummy-psp', Psp::getConfig()->getCurrentPsp());
    }

    public function test_it_throws_exception_if_i_get_config_for_non_configured_psps()
    {
        $this->expectException(InvalidPspException::class);

        LaravelPix::useAsDefaultPsp('not-configured-psp');

        $this->assertEquals('not-configured-psp', Psp::getDefaultPsp());

        Psp::getConfig()->getPspBaseUrl();
    }
}
