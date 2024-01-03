<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Grafite\MissionControl\JavaScriptService;

class JavaScriptServiceTest extends TestCase
{
    public $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new JavaScriptService('foobar', 'bash');
    }

    public function testRender()
    {
        $result = $this->service->render();

        $this->assertStringContainsString("window.addEventListener('error', function (event) {", $result);
        $this->assertStringContainsString('xhttp.setRequestHeader("Content-Type", "application/json; charset=UTF-8");', $result);
    }
}
