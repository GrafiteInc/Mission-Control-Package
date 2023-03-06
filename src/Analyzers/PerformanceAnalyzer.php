<?php

namespace Grafite\MissionControl\Analyzers;

use Exception;
use Illuminate\Support\Str;

class PerformanceAnalyzer
{
    public function getCpu()
    {
        $load = null;

        if (stristr(PHP_OS, "windows")) {
            $cmd = "wmic cpu get loadpercentage /all";
            @exec($cmd, $output);

            if ($output) {
                foreach ($output as $line) {
                    if ($line && preg_match("/^[0-9]+\$/", $line)) {
                        $load = $line;
                        break;
                    }
                }
            }
        }

        if (stristr(PHP_OS, "linux")) {
            $exec = shell_exec('top -b 1 -n 1 2>&1; echo $?');
            $idleString = (string) Str::of($exec)->substr(strpos($exec, ' id')-5, 6)->replace('%', '');
            $idle = (float) trim($idleString);

            $load = round(100 - $idle, 2);
        }

        if (stristr(PHP_OS, "darwin")) {
            $exec = shell_exec('top -l 1 -n 1 2>&1; echo $?');
            $idleString = (string) Str::of($exec)->substr(strpos($exec, 'idle')-7, 7)->replace('%', '');
            $idle = (float) trim($idleString);

            $load = round(100 - $idle, 2);
        }

        return $load;
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
