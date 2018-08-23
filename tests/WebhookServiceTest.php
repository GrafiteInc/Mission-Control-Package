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
        $this->assertEquals('foobar.foo', $this->request->url);
        $this->assertEquals([], $this->request->headers);
        $this->assertEquals([
            'title' => 'hello',
            'content' => 'foobar',
            'flag' => 'info',
        ], $this->request->query);
    }
}
