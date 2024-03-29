<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Grafite\MissionControl\Analyzers\PerformanceAnalyzer;

class PerformanceAnalyzerTest extends TestCase
{
    public $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new PerformanceAnalyzer;
    }

    public function testGetCpu()
    {
        $result = $this->service->getCpu(
            file(__DIR__.'/fixtures/stat1.txt'),
            file(__DIR__.'/fixtures/stat2.txt')
        );

        $this->assertGreaterThan(0, $result);
    }

    public function testGetMemory()
    {
        $result = $this->service->getMemory("              total        used        free      shared  buff/cache   available
Mem:        2041316     1387716       80444       83124      573156      381512
Swap:       1003516      496640      506876");

        $this->assertEquals(68.0, $result);
    }

    public function testGetStorage()
    {
        $result = $this->service->getStorage(1000, 9000);

        $this->assertEquals(89.0, $result);
    }
}
