<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Http;
use Grafite\MissionControl\SecurityService;

class SecurityServiceTest extends TestCase
{
    public $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new SecurityService('foobar', 'bash');
    }

    public function testRecordThreat()
    {
        Http::fake();

        $result = $this->service->recordThreat('invalid-ip', ['url' => 'who', 'query' => 'foo']);

        $this->assertEquals('invalid-ip', $result['type']);
    }

    public function testLookup()
    {
        Http::fake();

        $result = $this->service->lookup('1.1.1.1');

        $this->assertTrue(is_null($result[0]));
        $this->assertTrue(is_null($result[1]));
    }

    public function testFlag()
    {
        Http::fake();

        $result = $this->service->flag('1.1.1.1', 'flag');

        $this->assertTrue($result);
    }
}
