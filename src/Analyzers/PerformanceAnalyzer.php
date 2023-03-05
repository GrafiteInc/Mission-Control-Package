<?php

namespace Grafite\MissionControl\Analyzers;

use Exception;

class PerformanceAnalyzer
{
    public function getCpu()
    {
        $load = sys_getloadavg();

        $ncpu = 1;

        if (is_file('/proc/cpuinfo')) {
            $cpuinfo = file_get_contents('/proc/cpuinfo');
            preg_match_all('/^processor/m', $cpuinfo, $matches);
            $ncpu = count($matches[0]);
        }

        return round($load[0] / $ncpu, 4) * 100;
    }

    public function getMemory($data = null)
    {
        if (is_null($data)) {
            $data = shell_exec('free 2>&1');
        }

        if (strstr($data, 'command not found')) {
            throw new Exception("Unable to collect memory data, make sure you can run the command: 'free'", 1);
        }

        $free = (string) trim($data);

        $free_arr = explode("\n", $free);

        $mem = explode(" ", $free_arr[1]);
        $mem = array_filter($mem);
        $mem = array_merge($mem);

        $memory_usage = $mem[2] / $mem[1] * 100;

        if ($memory_usage == 'NAN') {
            $memory_usage = 0;
        }

        return round($memory_usage);
    }

    public function getStorage($free = null, $total = null)
    {
        if (is_null($free)) {
            $free = disk_free_space('/');
        }

        if (is_null($total)) {
            $total = disk_total_space('/');
        }

        $used = $total - $free;

        return round(($used / $total) * 100);
    }
}
