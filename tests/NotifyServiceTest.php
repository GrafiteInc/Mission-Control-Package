<?php

namespace Tests;

use Tests\TestCase;
use Grafite\MissionControl\NotifyService;
use Grafite\MissionControl\WebhookService;

class WebhookServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new NotifyService('foobar', 'bash');
        $this->request = new \Tests\MockRequest;
        $this->service->setCurl($this->request);
    }

    public function testSend()
    {
        $result = $this->service->send('hello', 'foobar', 'info');

        $this->assertTrue($result);
    }
}
