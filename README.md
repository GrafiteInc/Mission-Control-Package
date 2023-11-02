# Mission Control PHP Package

[![Build Status](https://github.com/GrafiteInc/Mission-Control-Package/workflows/PHP%20Package%20Tests/badge.svg?branch=main)](https://github.com/GrafiteInc/Mission-Control-Package/actions?query=workflow%3A%22PHP+Package+Tests%22)
[![Packagist](https://img.shields.io/packagist/dt/grafite/mission-control.svg)](https://packagist.org/packages/grafite/mission-control)
[![license](https://img.shields.io/github/license/mashape/apistatus.svg)](https://packagist.org/packages/grafite/mission-control-package)

**Mission Control PHP Package** - Send data to Grafite's Mission Control system to stay in control of your applications.

Grafite's Mission Control is an elegant Application Performance Management system. Forget being inundated with hundreds of charts and complex configurations for CMS websites, custom E-commerce platforms etc. Utilize the simple user interface, with specific data for high demand moments. Get notifications within minutes of your system being overloaded, or high levels of errors being triggered. Set it up in less than 5 minutes with your next deployment, and take back your weekends.

## Requirements

1. PHP 7.3+

### Composer

```php
composer require grafite/mission-control
```

### IssueService

IssueService lets you peak into your exceptions or any tagged messages you'd like to track. You can do so using the following methods:

```
use Grafite\MissionControl\IssueService;

try {
    // do some code
} catch (Exception $e) {
    $issueService = new IssueService('{API Token}', '{Project Key}');
    $issueService->exception($e);
}
```

Or if you just want to flag an potential issue or concern in your applicaiton:

```
use Grafite\MissionControl\IssueService;

$issueService = new IssueService('{API Token}', '{Project Key}');
$issueService->log('Anything you want to say goes here', 'tag');
```

##### Tags

Tags can be any terminology you want, to help sort through your issues.

### NotifyService

You can easily give yourself tagged notifications for your applications through this handy service.

```
use Grafite\MissionControl\NotifyService;

(new NotifyService('{API Token}', '{Project Key}'))->send('This is a title', 'info', 'This is a custom message');
```

### JavaScriptService

Want to get notified when users have JavaScript issues, just place this at the end of your scripts in your app templates.

```
echo (new Grafite\MissionControl\JavaScriptService($uuid, $key))->render();
```

### PerformanceService

Add this cron job to enable PerformanceService which scans your system to report back to mission control the state of your server.

```
*/5 * * * * /{app-path}/vendor/bin/performance {API token}
```

## License
Mission Control PHP Package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

### Bug Reporting and Feature Requests
Please add as many details as possible regarding submission of issues and feature requests

### Disclaimer
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
