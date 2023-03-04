<?php

namespace Grafite\MissionControl;

use Exception;
use Illuminate\Support\Facades\Http;
use Grafite\MissionControl\BaseService;

class NotifyService extends BaseService
{
    public $token;

    public $key;

    public $curl;

    protected $missionControlUrl;

    public function __construct($token = null, $key = null)
    {
        $this->token = $token;
        $this->key = $key;
        $this->missionControlUrl = $this->missionControlDomain('notify');
    }

    /**
     * Send the exception to Mission control.
     *
     * @param Exeption $exception
     *
     * @return bool
     */
    public function send($title, $tag, $message)
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
            'title' => $title,
            'message' => $message,
            'tag' => $tag,
        ];

        $response = Http::withHeaders($headers)->post($this->missionControlUrl, $query);

        if ($response->status() != 200) {
            $this->error('Unable to message Mission Control, please confirm your token and key');
        }

        return true;
    }
}
