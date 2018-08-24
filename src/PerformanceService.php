<?php

namespace Grafite\MissionControl;

use Exception;
use Grafite\MissionControl\Analyzers\PerformanceAnalyzer;
use Grafite\MissionControl\BaseService;

class PerformanceService extends BaseService
{
    public $token;

    public $curl;

    protected $missionControlUrl;

    public function __construct($token = null)
    {
        parent::__construct();

        if (is_null($token)) {
            throw new Exception("Missing token", 1);
        }

        $this->token = $token;
        $this->performanceAnalyzer = new PerformanceAnalyzer;
        $this->missionControlUrl = $this->missionControlDomain('performance');
    }

    /**
     * Send the exception to Mission control.
     *
     * @param Exeption $exception
     *
     * @return bool
     */
    public function sendPerformance()
    {
        $headers = [
            'token' => $this->token,
        ];

        $query = $this->getPerformance();

        $response = $this->curl::post($this->missionControlUrl, $headers, $query);

        if ($response->code != 200) {
            $this->error('Unable to message Mission Control, please confirm your token');
        }

        return true;
    }

    /**
     * Collect data and set report details.
     *
     * @return array
     */
    public function getPerformance()
    {
        return [
            'memory' => $this->performanceAnalyzer->getMemory(),
            'storage' => $this->performanceAnalyzer->getStorage(),
            'cpu' => $this->performanceAnalyzer->getCpu(),
        ];
    }
}
