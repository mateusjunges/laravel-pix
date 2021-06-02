<?php

namespace Junges\Pix\Tests\Api;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Http;
use Junges\Pix\Api\Api;
use Junges\Pix\Api\Auth;
use Junges\Pix\Tests\TestCase;
use Mockery as m;

class ApiTest extends TestCase
{
    public function tearDown(): void
    {
        m::close();

        Container::setInstance(null);
    }

    public function test_get_oauth_token()
    {
        $response = [
            'access_token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0eXBlIjoiYWNjZXNzX3Rva2VuIiwiY2xpZW50SWQiOiJDbGllbnRfSWRfOGFlZTUxZDk1ZGM3ZTA4NGFlYTFmY2Y3YzliNWJiZGFlNjFkMmVhYyIsImFjY291bnQiOjMwMDI3MywiYWNjb3VudF9jb2RlIjoiOWE1ZGY0YWJjYWZlMzY1OTg0MTVmZTI0MzcwYjRiZjUiLCJzY29wZXMiOlsiY29iLnJlYWQiLCJjb2Iud3JpdGUiLCJnbi5iYWxhbmNlLnJlYWQiLCJnbi5waXguZXZwLnJlYWQiLCJnbi5waXguZXZwLndyaXRlIiwiZ24uc2V0dGluZ3MucmVhZCIsImduLnNldHRpbmdzLndyaXRlIiwicGF5bG9hZGxvY2F0aW9uLnJlYWQiLCJwYXlsb2FkbG9jYXRpb24ud3JpdGUiLCJwaXgucmVhZCIsInBpeC53cml0ZSIsIndlYmhvb2sucmVhZCIsIndlYmhvb2sud3JpdGUiXSwiZXhwaXJlc0luIjozNjAwLCJjb25maWd1cmF0aW9uIjp7Ing1dCNTMjU2IjoiS0ZpOG5WM3F5Ky9Kb1lQbjVHRm5qc0J1TVlOOHNNUk44K1MxbUR2akZtWT0ifSwiaWF0IjoxNjIwNjkyNTM4LCJleHAiOjE2MjA2OTYxMzh9.U0tNrYjV0TaJuTqiHq4R5zyQh2leo8zT0DAnKhRjC54',
            'token_type'   => 'Bearer',
            'expires_in'   => 3600,
            'scope'        => 'cob.read cob.write payloadlocation.read payloadlocation.write pix.read pix.write webhook.read webhook.write',
        ];

        Http::fake([
            'https://pix.example.com/*' => Http::response($response, 200),
        ]);

        $api = new Api();

        $this->assertEquals($response, $api->getOauth2Token()->json());
    }

    public function test_it_can_instantiate_the_correct_class_for_each_psp()
    {
        $this->app['config']->set('laravel-pix.psp.dummy-psp.authentication_class', DummyPspAuth::class);

        $api = (new Api())->usingPsp('dummy-psp');

        $this->assertEquals('test_token', $api->getOauth2Token());
    }
}

class DummyPspAuth extends Auth
{
    public function getToken(string $scopes = null)
    {
        return 'test_token';
    }
}
