<?php

namespace Tests;

use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Http;
use Grafite\MissionControl\IssueService;

class IssueServiceTest extends TestCase
{
    public $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new IssueService('foo', 'bar');
    }

    public function testSend()
    {
        Http::fake();

        $result = $this->service->log('foobar', 'info');

        $this->assertTrue($result);
    }
}
