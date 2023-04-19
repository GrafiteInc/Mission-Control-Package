<?php

namespace Grafite\MissionControl\Analyzers;

use Exception;

class PerformanceAnalyzer
{
    public function getCpu($coreInfo1 = null, $coreInfo2 = null)
    {
        $stats1 = $this->getCoreInformation($coreInfo1);
        sleep(8);
        $stats2 = $this->getCoreInformation($coreInfo2);

        $cpu = $this->getCpuPercentages($stats1, $stats2);

        $cpuState = $cpu['cpu0']['user'] + $cpu['cpu0']['sys'] + $cpu['cpu0']['nice'];

        if ($cpuState < 0) {
            return 0;
        }

        return $cpuState;
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
        if (is_null($coreInfo)) {
            $coreInfo = file('/proc/stat');
        }

        $data = $coreInfo;
        $cores = [];
        foreach ($data as $line) {
            if (preg_match('/^cpu[0-9]/', $line)) {
                $info = explode(' ', $line);
                $cores[] = [
                    'user' => $info[1],
                    'nice' => $info[2],
                    'sys' => $info[3],
                    'idle' => $info[4],
                ];
            }
        }
        return $cores;
    }

    public function getCpuPercentages($stat1, $stat2)
    {
        if (count($stat1) !== count($stat2)) {
            return;
        }

        $cpus = [];

        for ($i = 0, $l = count($stat1); $i < $l; $i++) {
            $dif = [];
            $dif['user'] = $stat2[$i]['user'] - $stat1[$i]['user'];
            $dif['nice'] = $stat2[$i]['nice'] - $stat1[$i]['nice'];
            $dif['sys'] = $stat2[$i]['sys'] - $stat1[$i]['sys'];
            $dif['idle'] = $stat2[$i]['idle'] - $stat1[$i]['idle'];
            $total = array_sum($dif);
            $cpu = [];

            foreach ($dif as $x => $y) {
                $cpu[$x] = 0;

                if ($y !== 0) {
                    $cpu[$x] = round($y / $total * 100, 1);
                }
            }

            $cpus['cpu' . $i] = $cpu;
        }

        return $cpus;
    }
}
