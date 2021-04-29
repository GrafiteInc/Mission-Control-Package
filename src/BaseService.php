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
        $domain = 'https://missioncontrolapp.io';

        if (getenv('MISSION_CONTROL_URL')) {
            $domain = getenv('MISSION_CONTROL_URL');
        }

        return "{$domain}/api/{$url}";
    }

    public function error($message)
    {
        return error_log($message);
    }
}
