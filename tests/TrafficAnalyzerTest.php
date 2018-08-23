<?php

namespace Tests;

use Grafite\MissionControl\Analyzers\TrafficAnalyzer;
use Tests\TestCase;

class TrafficAnalyzerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->service = new TrafficAnalyzer;
    }

    public function testAnalyze()
    {
        $result = $this->service->analyze(__DIR__.'/fixtures/access.log_example', null);

        $this->assertEquals(0, $result['hits']);
        $this->assertEquals(0, $result['total_data_sent']);
        $this->assertEquals('N/A', $result['most_common_method']);
        $this->assertEquals('N/A', $result['most_common_url']);
        $this->assertEquals('N/A', $result['most_common_user_agent']);
    }
}
