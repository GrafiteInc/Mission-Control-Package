<?php

namespace Tests;

use Grafite\MissionControl\IssueService;
use Tests\TestCase;

class IssueServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new IssueService('foo', 'bar');
        $this->request = new \Tests\MockRequest;
        $this->service->setCurl($this->request);
    }

    public function testSend()
    {
        $result = $this->service->log('foobar', 'info');

        $this->assertTrue($result);
    }
}
