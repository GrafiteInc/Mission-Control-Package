<?php

namespace Grafite\MissionControl\Analyzers;

use Exception;

class PerformanceAnalyzer
{
    // Pulled from: https://gist.github.com/fhdalikhan/c37ee69a80b11cf3f102fc4fc175733b
    public function getCpu($path = '/proc/stat')
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

        if (! stristr(PHP_OS, "windows")) {
            if (is_readable($path)) {
                // Collect 2 samples - each with 1 second period
                // See: https://de.wikipedia.org/wiki/Load#Der_Load_Average_auf_Unix-Systemen
                $statData1 = $this->getLinuxServerLoad($path);
                sleep(1);
                $statData2 = $this->getLinuxServerLoad($path);

                if (
                    (! is_null($statData1)) &&
                    (! is_null($statData2))
                ) {
                    // Get difference
                    $statData2[0] -= $statData1[0];
                    $statData2[1] -= $statData1[1];
                    $statData2[2] -= $statData1[2];
                    $statData2[3] -= $statData1[3];

                    // Sum up the 4 values for User, Nice, System and Idle and calculate
                    // the percentage of idle time (which is part of the 4 values!)
                    $cpuTime = $statData2[0] + $statData2[1] + $statData2[2] + $statData2[3];

                    if ($cpuTime !== 0) {
                        // Invert percentage to get CPU time, not idle time
                        $load = 100 - ($statData2[3] * 100 / $cpuTime);
                    }
                }
            }
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

    // Pulled from: https://gist.github.com/fhdalikhan/c37ee69a80b11cf3f102fc4fc175733b
    protected function getLinuxServerLoad($path)
    {
        if (is_readable($path)) {
            $stats = file_get_contents($path);

            if ($stats !== false) {
                // Remove double spaces to make it easier to extract values with explode()
                $stats = preg_replace("/[[:blank:]]+/", " ", $stats);

                // Separate lines
                $stats = str_replace(array("\r\n", "\n\r", "\r"), "\n", $stats);
                $stats = explode("\n", $stats);

                // Separate values and find line for main CPU load
                foreach ($stats as $statLine) {
                    $statLineData = explode(" ", trim($statLine));

                    // Found!
                    if (
                        (count($statLineData) >= 5) &&
                        ($statLineData[0] == "cpu")
                    ) {
                        return array(
                            $statLineData[1],
                            $statLineData[2],
                            $statLineData[3],
                            $statLineData[4],
                        );
                    }
                }
            }
        }

        return null;
    }
}
