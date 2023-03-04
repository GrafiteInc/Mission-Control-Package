<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Grafite\MissionControl\NotifyService;

class NotifyServiceTest extends TestCase
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
