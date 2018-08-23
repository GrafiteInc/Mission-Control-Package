<?php

namespace Grafite\MissionControl;

use Unirest\Request as UniRequest;

class WebhookService
{
    public $webhook;

    public function __construct($webhook = null)
    {
        if (!is_null($webhook)) {
            $this->webhook = $webhook;
        }
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

        $response = UniRequest::post($this->webhook, $headers, $query);

        if ($response->code != 200) {
            error_log('Unable to message Mission Control, please confirm your token');
        }
    }
}
