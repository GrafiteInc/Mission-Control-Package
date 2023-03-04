<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Grafite\MissionControl\StatsService;

class StatsServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new StatsService('foobar', 'bash');
        $this->request = new \Tests\MockRequest;
        $this->service->setCurl($this->request);
    }

    public function testSend()
    {
        $result = $this->service->send(['users' => 30, 'jobs' => 10]);

        $this->assertTrue($result);
    }
}
