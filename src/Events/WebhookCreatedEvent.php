<?php

namespace Junges\Pix\Events;

class WebhookCreatedEvent
{
    public array $webhook;

    public function __construct(array $webhook)
    {
        $this->webhook = $webhook;
    }
}