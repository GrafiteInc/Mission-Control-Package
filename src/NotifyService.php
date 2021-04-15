<?php

namespace Grafite\MissionControl;

use Exception;
use Grafite\MissionControl\BaseService;

class NotifyService extends BaseService
{
    public $token;

    public $key;

    public $curl;

    protected $missionControlUrl;

    public function __construct($token = null, $key = null)
    {
        parent::__construct();

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
            'token' => $this->token,
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

        $response = $this->curl::post($this->missionControlUrl, $headers, $query);

        if ($response->code != 200) {
            $this->error('Unable to message Mission Control, please confirm your token and key');
        }

        return true;
    }
}
