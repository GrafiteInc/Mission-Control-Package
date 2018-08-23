<?php

namespace Tests;

class MockRequest
{
    public $url;
    public $headers;
    public $query;
    public $code;

    public function post($url, $headers, $query)
    {
        $this->url = $url;
        $this->headers = $headers;
        $this->query = $query;
        $this->code = 200;

        return $this;
    }
}
