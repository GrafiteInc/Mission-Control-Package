<?php

namespace Grafite\MissionControl;

class BaseService
{
    public function missionControlDomain($url)
    {
        return 'https://getmissioncontrol.io/api/'.$url;
    }
}
