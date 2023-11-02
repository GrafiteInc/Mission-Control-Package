<?php

namespace Grafite\MissionControl;

use Exception;
use Illuminate\Support\Facades\Http;
use Grafite\MissionControl\BaseService;

class SecurityService extends BaseService
{
    public $token;

    public $key;

    public $curl;

    protected $missionControlUrl;

    public function __construct($token = null, $key = null)
    {
        $this->token = $token;
        $this->key = $key;
    }

    /**
     * Mark an IP as a bad or not bad Actor.
     *
     * @param Exeption $exception
     *
     * @return bool
     */
    public function flag(string $ipAddress, $type = 'flag')
    {
        $this->missionControlUrl = $this->missionControlDomain('security/flag');

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
            'type' => $type,
            'address' => $ipAddress,
        ];

        $response = Http::withHeaders($headers)->retry(3, 100)->post($this->missionControlUrl, $query);

        if ($response->status() != 200) {
            $this->error($response->reason());
        }

        return true;
    }

    /**
     * Send the request details of the threat.
     *
     * @param Exeption $exception
     *
     * @return bool
     */
    public function recordThreat(string $type, array $payload)
    {
        $this->missionControlUrl = $this->missionControlDomain('security/threat');

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
            'type' => $type,
            'data' => array_merge(['input' => $payload], (new IssueService())->defaultRequest()),
        ];

        $response = Http::withHeaders($headers)->retry(3, 100)->post($this->missionControlUrl, $query);

        if ($response->status() != 200) {
            $this->error($response->reason());
        }

        return $query;
    }

    /**
     * Lookup IP for access.
     *
     * @param Exeption $exception
     *
     * @return bool
     */
    public function lookup(string $ipAddress)
    {
        $this->missionControlUrl = $this->missionControlDomain('security/lookup');

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
            'address' => $ipAddress,
        ];

        $response = Http::withHeaders($headers)->retry(3, 100)->post($this->missionControlUrl, $query);

        if ($response->status() != 200) {
            $this->error($response->reason());
        }

        return [
            $response->json('ip'),
            $response->json('country'),
        ];
    }
}
