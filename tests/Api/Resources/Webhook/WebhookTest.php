<?php

namespace Junges\Pix\Tests\Api\Resources\Webhook;

use Illuminate\Support\Facades\Http;
use Junges\Pix\Tests\TestCase;

class WebhookTest extends TestCase
{
    public function test_it_can_create_a_webhook()
    {
        Http::fake([
            'pix.example.com/v2/*' => Http::response()
        ]);
    }

    public function test_it_can_delete_a_webhook()
    {

    }

    public function test_it_can_get_webhooks_by_pix_keys()
    {

    }

    public function test_it_can_get_all_webhooks()
    {

    }
}