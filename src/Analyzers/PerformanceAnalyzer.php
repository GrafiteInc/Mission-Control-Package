<?php

namespace Grafite\MissionControl\Analyzers;

use Exception;

class PerformanceAnalyzer
{
    public function getCpu($coreInfo1 = null, $coreInfo2 = null)
    {
        sleep(15);
        $cpu = $this->getCoreInformation($coreInfo1);

        if ($cpu < 0) {
            return 0;
        }

        return $cpu;
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

    public function getCoreInformation($coreInfo)
    {
        return match (PHP_OS_FAMILY) {
            'Darwin' => (int) `top -l 1 | grep -E "^CPU" | tail -1 | awk '{ print $3 + $5 }'`,
            'Linux' => (int) `top -bn1 | grep -E '^(%Cpu|CPU)' | awk '{ print $2 + $4 }'`,
            default => throw new Exception('We cannot currently support '.PHP_OS_FAMILY),
        };
    }
}
