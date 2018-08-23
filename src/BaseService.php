<?php

namespace Grafite\MissionControl;

use Unirest\Request as UniRequest;

class BaseService
{
    public $curl;

    public function __construct()
    {
        $this->curl = new UniRequest;
    }

    public function setCurl($instance)
    {
        $this->curl = $instance;
    }

    public function missionControlDomain($url)
    {
        return 'https://getmissioncontrol.io/api/'.$url;
    }
}
