# Mission Control PHP Package

[![Build Status](https://travis-ci.org/GrafiteInc/Mission-Control-Package.svg?branch=master)](https://travis-ci.org/GrafiteInc/Mission-Control-Package)
[![Packagist](https://img.shields.io/packagist/dt/grafite/mission-control.svg)](https://packagist.org/packages/grafite/mission-control)
[![license](https://img.shields.io/github/license/mashape/apistatus.svg)](https://packagist.org/packages/grafite/mission-control-package)

**Mission Control PHP Package** - Send data to Grafite's Mission Control system to stay in control of your applications.

Grafite's Mission Control is an elegant Application Performance Management system. Forget being inundated with hundreds of charts and complex configurations for CMS websites, custom E-commerce platforms etc. Utilize the simple user interface, with specific data for high demand moments. Get notifications within minutes of your system being overloaded, or high levels of errors being triggered. Set it up in less than 5 minutes with your next deployment, and take back your weekends.

## Requirements

1. PHP 7.1+

### Composer

```php
composer require grafite/mission-control
```

### IssueService

IssueService lets you peak into your exceptions or any flagged messages you'd like to track. You can do so using the following methods:

```
use Grafite\MissionControl\IssueService;

try {
    // do some code
} catch (Exception $e) {
    $issueService = new IssueService('{API Token}');
    $issueService->exception($e);
}
```

Or if you just want to flag an potential issue or concern in your applicaiton:

```
use Grafite\MissionControl\IssueService;

$issueService = new IssueService('{API Token}');
$issueService->log('Anything you want to say goes here', 'flag');
```

##### Flags

Flags can be any terminology you want, to help sort through your issues.

### WebhookService

You can easily tie the webhooks into your application with this package using class and method:

```
use Grafite\MissionControl\WebhookService;

(new WebhookService('{your-projects-webhook}'))->send('This is a title', 'This is a custom message', 'info');
```

### PerformanceService

Add this cron job to enable PerformanceService which scans your system to report back to mission control the state of your server.

```
*/5 * * * * /{app-path}/vendor/bin/performance {API token}
```

### TrafficService

Want to track some basic inforation about your web traffic? You'll need to make sure you've enabled `access.log` tracking, then add this cron job to your server.

```
*/5 * * * * /{app-path}/vendor/bin/traffic {API token} {path-to-access.log} {format --optional (nginx default)}
```

#### TrafficService formats

nginx: %a %l %u %t "%m %U %H" %>s %O "%{Referer}i" \"%{User-Agent}i"
apache: %h %l %u %t "%r" %>s %b

#### Quick tip for Forge users

This simple command can enable your access logs after you restart Ngnix.

```
sudo su
sed -i -e 's/access_log off;/access_log \/var\/log\/nginx\/{domain}-access.log;/g' /etc/nginx/sites-available/{domain}
```

Your log should then be `/var/log/nginx/{domain}-access.log`

## License
Mission Control PHP Package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

### Bug Reporting and Feature Requests
Please add as many details as possible regarding submission of issues and feature requests

### Disclaimer
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
