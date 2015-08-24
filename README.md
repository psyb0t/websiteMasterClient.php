# websiteMasterClient.php

## Prerequisites

__An HTTP server__

nginx
```
apt-get install nginx
```
apache
```
apt-get install apache2
```

__PHP5__

```
apt-get install php5-fpm php5-curl
```

## Installation

In the host index directory:
```
git clone git@github.com:psyb0t/websiteMasterClient.php.git .
git submodule init
git submodule update

```

__[Note]__

For caching, the directory will need to be writable by the script.

Either `chown -R [http-server-user]:[http-server-user] /path/to/index`

or `chmod 666 /path/to/index`

__HTTP Server Setup__

You will need rewrite rules in the HTTP server configuration to redirect any URL to index.php

__nginx__:
```
location / {
  rewrite ^/(.*?)$ /index.php;
}
```

__apache .htaccess__:
```
RewriteEngine On 
RewriteRule ^/(.*?)$ /index.php
```

## Configuration

__config.php__ contains constant definitions which need to be setup as you need

_wm_server_address_

The address where the websiteMaster.php server is located at.

_wm_server_timeout_

The timeout(in seconds) to wait for the WM server to respond(if timeout exceeds, the page is shown from cache, if present)

_absolute_path_

You should leave this as it is

_caching_

Enable or disable caching. It's recommended that you cache all of your requests once you're done testing

_cache_dir_

Choose where you want the cache files to go to. The target directory must be writable by the script.

_cache_life_

The time(in seconds) for which a cache file will be served until a new request will be sent to the server and renew it.

__Default configuration__

```
define('wm_server_address', 'http://example.org/websiteMaster/');
define('wm_server_timeout', 5);
define('absolute_path', dirname(__FILE__).'/');
define('caching', true);
define('cache_dir', absolute_path.'cache/');
define('cache_life', 3600);
```
