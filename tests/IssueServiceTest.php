<?php

namespace Tests;

use Grafite\MissionControl\IssueService;
use Tests\TestCase;

class IssueServiceTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->service = new IssueService('foo');
        $this->request = new \Tests\MockRequest;
        $this->service->setCurl($this->request);
    }

    public function testSend()
    {
        $result = $this->service->log('foobar', 'info');

        $this->assertTrue($result);
        $this->assertEquals('https://getmissioncontrol.io/api/issue', $this->request->url);
        $this->assertEquals([
            'token' => 'foo'
        ], $this->request->headers);

        $this->assertContains('log', $this->request->query);
        $this->assertEquals('', $this->request->query['report_server_name']);
        $this->assertContains('{"flag":"info","message":"foobar"}', $this->request->query);
    }
}
