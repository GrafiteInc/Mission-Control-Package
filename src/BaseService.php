<?php

namespace Grafite\MissionControl;

class BaseService
{
    public function missionControlDomain($url)
    {
        return 'http://missioncontrol.test/api/'.$url;
        return 'https://getmissioncontrol.io/api/'.$url;
    }
}
