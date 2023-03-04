<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Http;
use Grafite\MissionControl\StatsService;

class StatsServiceTest extends TestCase
{
    public $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new StatsService('foobar', 'bash');
    }

    public function testSend()
    {
        Http::fake();

        $result = $this->service->send(['users' => 30, 'jobs' => 10]);

        $this->assertTrue($result);
    }
}
