<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Http;
use Grafite\MissionControl\NotifyService;

class NotifyServiceTest extends TestCase
{
    public $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new NotifyService('foobar', 'bash');
    }

    public function testSend()
    {
        Http::fake();

        $result = $this->service->send('hello', 'foobar', 'info');

        $this->assertTrue($result);
    }
}
