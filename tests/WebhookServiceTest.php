<?php

namespace Tests;

use Grafite\MissionControl\WebhookService;
use Tests\TestCase;

class WebhookServiceTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->service = new WebhookService('foobar.foo');
        $this->request = new \Tests\MockRequest;
        $this->service->setCurl($this->request);
    }

    public function testSend()
    {
        $result = $this->service->send('hello', 'foobar', 'info');

        $this->assertTrue($result);
    }
}
