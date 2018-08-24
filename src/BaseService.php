<?php

namespace Grafite\MissionControl;

use Unirest\Request;

class BaseService
{
    public $curl;

    public function __construct()
    {
        $this->curl = new Request();
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
