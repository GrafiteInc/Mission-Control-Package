<?php

namespace Grafite\MissionControl;

use Exception;
use Grafite\MissionControl\BaseService;

class IssueService extends BaseService
{
    public $token;

    public $key;

    public $curl;

    public $baseRequest;

    protected $missionControlUrl;

    public function __construct($token = null, $key = null)
    {
        parent::__construct();

        $this->token = $token;
        $this->key = $key;
        $this->missionControlUrl = $this->missionControlDomain('issue');
        $this->baseRequest = $this->defaultRequest();
    }

    /**
     * Override the details of the request.
     *
     * @param array $values
     * @return self
     */
    public function setBaseRequest($values)
    {
        $this->baseRequest = $values;

        return $this;
    }

    /**
     * Send the exception to Mission control.
     *
     * @param Exeption $exception
     *
     * @return bool
     */
    public function exception($exception)
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

        $query = $this->processException($exception);

        $response = $this->curl::post($this->missionControlUrl, $headers, $query);

        if ($response->code != 200) {
            $this->error('Unable to message Mission Control, please confirm your token');
        }

        return true;
    }

    /**
     * Send the log to Mission Control
     *
     * @param  string $message
     * @param  string $tag
     *
     * @return bool
     */
    public function log($message, $tag)
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

        $query = $this->processLog($message, $tag);

        $response = $this->curl::post($this->missionControlUrl, $headers, $query);

        if ($response->code != 200) {
            $this->error('Unable to message Mission Control, please confirm your token');
        }

        return true;
    }

    /**
     * Collect data and set report details.
     *
     * @param Exception $exception
     *
     * @return array
     */
    public function processException($exception)
    {
        $requestDetails = [
            'type' => 'exception',
            'data' => json_encode([
                'exception_content' => $exception->getMessage() ?? 'No message',
                'exception_trace' => $exception->getTrace(),
                'exception_file' => $exception->getFile(),
                'exception_line' => $exception->getLine(),
                'file_contents' => file_get_contents($exception->getFile()),
                'headers' => $this->headers(),
            ]),
        ];

        return array_merge($this->baseRequest, $requestDetails);
    }

    /**
     * Collect data and set report details.
     *
     * @param String $message
     * @param String $tag
     *
     * @return array
     */
    public function processLog($message, $tag)
    {
        $requestDetails = [
            'type' => 'log',
            'data' => json_encode([
                'tag' => $tag,
                'message' => $message,
                'headers' => $this->headers(),
            ]),
        ];

        return array_merge($this->baseRequest, $requestDetails);
    }

    /**
     * Collect basic server info
     *
     * @return array
     */
    protected function defaultRequest()
    {
        return [
            'report_referer' => $this->server('HTTP_REFERER', ''),
            'report_user_agent' => $this->server('HTTP_USER_AGENT', ''),
            'report_host' => $this->server('HTTP_HOST', ''),
            'report_server_name' => $this->server('SERVER_NAME', ''),
            'report_remote_addr' => $this->server('REMOTE_ADDR', ''),
            'report_server_software' => $this->server('SERVER_SOFTWARE', ''),
            'report_uri' => $this->server('REQUEST_URI', ''),
            'report_time' => $this->server('REQUEST_TIME', ''),
            'report_method' => $this->server('REQUEST_METHOD', ''),
            'report_query' => $this->server('QUERY_STRING', ''),
            'app_base' => $this->server('DOCUMENT_ROOT', ''),
        ];
    }

    /**
     * Get the server value or pass back the default
     *
     * @param  string $index
     * @param  string $default
     *
     * @return string
     */
    public function server($index, $default)
    {
        return (isset($_SERVER[$index])) ? $_SERVER[$index] : $default;
    }

    /**
     * Define the headers from the request
     *
     * @return array
     */
    public function headers()
    {
        $headers = [];

        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }

        unset($headers['Cookie']);

        return $headers;
    }
}
