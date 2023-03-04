<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Grafite\MissionControl\DependencyService;

class DependencyServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new DependencyService('foobar', 'bash');
        $this->request = new \Tests\MockRequest;
        $this->service->setCurl($this->request);
    }

    public function testSend()
    {
        $result = $this->service->send(['database' => true, 'redis' => true]);

        $this->assertTrue($result);
    }
}
