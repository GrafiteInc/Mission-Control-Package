<?php

namespace Grafite\MissionControl;

use Exception;
use Grafite\MissionControl\BaseService;

class WebhookService extends BaseService
{
    public $webhook;

    public $curl;

    public function __construct($webhook = null)
    {
        parent::__construct();

        if (is_null($webhook)) {
            throw new Exception("Missing Webhook", 1);
        }

        $this->webhook = $webhook;
    }

    /**
     * Send the data to Mission Control
     *
     * @param  string $title
     * @param  string $content
     * @param  string $flag
     *
     * @return bool
     */
    public function send($title, $content, $flag)
    {
        $headers = [];

        $query = [
            'title' => $title,
            'content' => $content,
            'flag' => $flag,
        ];

        $response = $this->curl::post($this->webhook, $headers, $query);

        if ($response->code != 200) {
            $this->error('Unable to message Mission Control, please confirm your webhook');
        }

        return true;
    }
}
