This repository contains reference implementation of Heartbeat server
used by Server Farmer.

## Compatibility

This version is meant for installing on shared hosting environments,
with PHP 5.x or 7.x (any version should work), but without access to
memcached, redis or any other caching mechanism.


## How to use

#### Feeding data

You just have to install *sf-monitoring-heartbeat* extension on your
server(s):

```
/opt/farm/scripts/setup/extension.sh sf-monitoring-heartbeat
```

Or you can add if to `.default.extensions` file in your forked Server
Farmer repository, to cause installing it automatically on all servers.

Next, check `functions.custom` file for `heartbeat_url` function. It
should point to your heartbeat instance. By default, it points to
https://serwer1598662.home.pl/heartbeat/, which is the public instance.

*Note that the public instance employs additional logging and protections
agains possible scanning and/or other malicious behavior.*

#### Monitoring and alerting

You can use any monitoring solution, either internal (like eg. Nagios)
or external (eg. Uptimerobot, Pingdom, Statuscake), that can perform
https keyword monitoring (send https requests and check the response
for the defined keyword).

Example heartbeat URL for MySQL instance on server.yourdomain.com server:

```
https://serwer1598662.home.pl/heartbeat/query.php?id=mysql_server_yourdomain_com
```

and the monitoring system should check for "ALIVE" keyword in the response.



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
