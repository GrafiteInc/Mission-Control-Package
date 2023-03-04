<?php

namespace Tests;

use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Http;
use Grafite\MissionControl\DependencyService;

class DependencyServiceTest extends TestCase
{
    public $request;
    public $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new DependencyService('foobar', 'bash');
    }

    public function testSend()
    {
        Http::fake();

        $result = $this->service->send(['database' => true, 'redis' => true]);

        $this->assertTrue($result);
    }
}
