<?php

namespace Grafite\MissionControl;

use Grafite\MissionControl\BaseService;

class JavaScriptService extends BaseService
{
    public $uuid;

    public $key;

    protected $missionControlUrl;

    public function __construct($uuid, $key)
    {
        $this->uuid = $uuid;
        $this->key = $key;
    }

    /**
     * Override the details of the request.
     *
     * @param array $values
     * @return self
     */
    public function render()
    {
        $uuid = $this->uuid;
        $key = $this->key;
        $url = $this->missionControlDomain('webhook');

        $script = <<<EOL
window.addEventListener('error', function (event) {
    const xhttp = new XMLHttpRequest();
    xhttp.open("POST", "${url}/api/webhook/${uuid}/issue?key=${key}", true);
    xhttp.setRequestHeader("Content-Type", "application/json; charset=UTF-8");
    xhttp.send(JSON.stringify({
        source: 'JavaScript',
        message: `\${event.message}: on line \${event.lineno} at column \${event.colno} within \${event.filename}`,
        stack: event.error.stack,
        tag: "error"
    }));

    return false;
});
EOL;

        return $script;
    }
}
