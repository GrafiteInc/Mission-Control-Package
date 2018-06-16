<?php

namespace Grafite\MissionControl\Services;

class PerformanceAnalyzer
{
    public function getCpu()
    {
        $averages = sys_getloadavg();

        return $averages[0];
    }

    public function getMemory()
    {
        $free = shell_exec('free');
        $free = (string) trim($free);

        $free_arr = explode("\n", $free);

        $mem = explode(" ", $free_arr[1]);
        $mem = array_filter($mem);
        $mem = array_merge($mem);

        $memory_usage = $mem[2] / $mem[1] * 100;

        return round($memory_usage);
    }

    public function getStorage()
    {
        return round((disk_free_space('/') / disk_total_space('/')) * 100);
    }
}
