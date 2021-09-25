<?php

namespace Junges\Pix\Tests;

use Junges\Pix\Providers\PixServiceProvider;
use Junges\Pix\Support\Endpoints;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public string $cpfKey = '497.442.110-75';
    public string $randomKey = '7d9f0335-8dcc-4054-9bf9-0dbd61d36906';
    public string $cnpjKey = '07.949.599/0001-53';
    public string $phoneNumberKey = '+5542999999999';
    public string $emailKey = 'mateus@junges.dev';
    public string $dummyPspUrl = 'https://pix.dummy-psp.com/v2/*';

    public function setUp(): void
    {
        parent::setUp();
        (new PixServiceProvider($this->app))->boot();
    }

    public function getPackageProviders($app)
    {
        return [
            PixServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('laravel-pix.country_phone_prefix', '+55');
        $app['config']->set('laravel-pix.transaction_currency_code', 986);
        $app['config']->set('laravel-pix.country_code', 'BR');
        $app['config']->set('laravel-pix.gui', 'br.gov.bcb.pix');
        $app['config']->set('laravel-pix.psp.default.base_url', 'https://pix.example.com/v2');
        $app['config']->set('laravel-pix.psp.default.oauth_token_url', 'https://pix.example.com/oauth/token');
        $app['config']->set('laravel-pix.psp.default.authentication_class', \Junges\Pix\Api\Contracts\AuthenticatesPSPs::class);
        $app['config']->set('laravel-pix.psp.default.resolve_endpoints_using', Endpoints::class);
        $app['config']->set('laravel-pix.psp.dummy-psp.base_url', 'https://pix.dummy-psp.com/v2');
        $app['config']->set('laravel-pix.psp.dummy-psp.oauth_token_url', 'https://pix.dummy-psp.com/oauth/token');
        $app['config']->set('laravel-pix.psp.dummy-psp.resolve_endpoints_using', EndpointsResolver::class);
    }
}
