## Overview

Heartbeat is a Server Farmer subproject, that extends functionally of your existing monitoring/alerting solution by providing abilities to monitor network services, Docker containers, virtual machines, condition of hard drives and many other aspects of a Linux server.

Heartbeat is divided into [client](https://github.com/serverfarmer/heartbeat-linux) and server parts. Client part is responsible for collecting data and sending reports every 2 minutes to server. Server part is responsible for storing information about each reported service and responding to queries from your monitoring/alerting solution, if this particular service is (still) alive or not.

Heartbeat can work with any monitoring/alerting system, that supports http(s) keyword monitoring, including:
- public: StatusCake, Uptimerobot, Pingdom etc.
- local: Nagios, Icinga, Zabbix, PRTG etc.


## Installation

Client part is either installed manually or automatically by Server Farmer  - installation process is described in [heartbeat-linux repository](https://github.com/serverfarmer/heartbeat-linux).

Server part is installed on webserver with PHP support. Version provided in this repository (the whole `heartbeat` subdirectory) can be just put into htdocs directory. It doesn't require memcached or any other dependencies, instead is uses `files` subdirectory to store temporary files - to ensure high performance, you should put this subdirectory name into `/etc/fstab` file, eg.:

`tmpfs /var/www/html/heartbeat/files tmpfs noatime,size=512m 0 0`

then mount it (by executing `mount /var/www/html/heartbeat/files`) and put into vhost configuration:

```
<Directory /var/www/html/heartbeat/files>
    Order deny,allow
    Deny from all
</Directory>
```

**If you don't care about high performance, or SSD drives health, you can skip fstab/vhost configuration** - just copying `heartbeat` subdirectory to your hosting directory is perfectly enough to make it work.


## How it works

Server part receives 2 types of requests:

- reports from monitored hosts (handled by `index.php` file), containing the hostname and a list of detected services - such reports are send by each host every 2 minutes and reported services are considered alive for 270 seconds since last report (you can adjust this time, also per service name)
- queries from monitoring/alerting solution (handled by `query.php` file), separately for each monitored host and service - and replies with either `ALIVE` or `DEAD` keyword

So, Heartbeat server is in fact just a kind of dumb proxy between monitored servers and your monitoring/alerting solution. All checks, as well as related logic (eg. list of notified people for each check) should be created directly in your monitoring/alerting solution (which does not even need to be accessible from monitored servers).


## Query URL format

Assuming that:
- your Heartbeat server has address `http://heartbeat.yourdomain.com/heartbeat/`
- your example monitored host has hostname `yourserver.yourdomain.com`

this is the complete URL that checks for `ssh` service running on this host:

`http://heartbeat.yourdomain.com/heartbeat/query.php?id=ssh_yourserver_yourdomain_com`

Rules:
- everything is converted to lowercase
- underlines, colons and slashes are replaced with dashes
- dots in hostnames are replaced with underlines
- network service names are listed in `/opt/heartbeat/scripts/checks/services.sh` script


## Performance

Single AWS `t2.micro` instance, storing temporary files on `tmpfs` filesystem, can handle over 3000 individual checks without any performance issues, assuming that queries from monitoring system are done via http (no encryption), every 1 minute.

Note that you can use different addresses for reporting data from monitored hosts, and for querying (in particular, you can use https for reporting and http for querying over internal network).


## Compatibility

Heartbeat server requires PHP 5.2 or later, and is 100% compatible with PHP 7.x. It doesn't have any dependencies (eg. memcached, Redis, specific libraries or frameworks), however for maximum performance, `files` subdirectory should be mounted as `tmpfs` (see Installation section above).


## How to contribute

We are welcome to contributions of any kind: bug fixes, versions for
different environments, using particular caching services etc.

If you want to contribute:
- fork this repository and clone it to your machine
- create a feature branch and do the change inside it
- push your feature branch to github and create a pull request


## License

|                      |                                          |
|:---------------------|:-----------------------------------------|
| **Author:**          | Tomasz Klim (<opensource@tomaszklim.pl>) |
| **Copyright:**       | Copyright 2017-2018 Tomasz Klim          |
| **License:**         | MIT                                      |

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
