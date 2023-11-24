<?php

namespace Grafite\MissionControl;

use Exception;
use Illuminate\Support\Facades\Http;
use Grafite\MissionControl\BaseService;
use Illuminate\Http\Client\ConnectionException;

class DependencyService extends BaseService
{
    public $token;

    public $key;

    public $curl;

    protected $missionControlUrl;

    public function __construct($token = null, $key = null)
    {
        $this->token = $token;
        $this->key = $key;
        $this->missionControlUrl = $this->missionControlDomain('status');
    }

    /**
     * Send the exception to Mission control.
     *
     * @param Exeption $exception
     *
     * @return bool
     */
    public function send(array $payload)
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

        $query = [
            'type' => 'dependencies',
            'data' => $payload,
        ];

        try {
            $response = Http::withHeaders($headers)->retry(3, 100)->post($this->missionControlUrl, $query);

            if ($response->status() != 200) {
                $this->error($response->reason());
            }

            return true;
        } catch (ConnectionException $th) {
            // There is no need to log this as an issue.
        }
    }
}
