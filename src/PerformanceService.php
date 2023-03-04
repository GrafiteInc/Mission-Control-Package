<?php

namespace Grafite\MissionControl;

use Exception;
use Illuminate\Support\Facades\Http;
use Grafite\MissionControl\BaseService;
use Grafite\MissionControl\IssueService;
use Grafite\MissionControl\Analyzers\PerformanceAnalyzer;

class PerformanceService extends BaseService
{
    public $token;
    public $curl;
    public $performanceAnalyzer;
    public $issueService;
    protected $missionControlUrl;

    public function __construct($token = null, $key = null)
    {
        $this->token = $token;
        $this->key = $key;

        $this->performanceAnalyzer = new PerformanceAnalyzer;
        $this->issueService = new IssueService($this->token, $this->key);
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
            'Authorization' => 'Bearer ' . $this->token,
            'key' => $this->key,
        ];

        if (is_null($this->token)) {
            throw new Exception("Missing token", 1);
        }

        if (is_null($this->key)) {
            throw new Exception("Missing key", 1);
        }

        $query = $this->getPerformance();

        $response = Http::withHeaders($headers)->post($this->missionControlUrl, $query);

        if ($response->status() != 200) {
            $this->error('Unable to message Mission Control, please confirm your token and key');
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
        try {
            return [
                'memory' => $this->performanceAnalyzer->getMemory(),
                'storage' => $this->performanceAnalyzer->getStorage(),
                'cpu' => $this->performanceAnalyzer->getCpu(),
            ];
        } catch (Exception $e) {
            $this->issueService->exception($e);
        }
    }
}
