<?php
header('Content-Type: text/plain');

require('httpRequest/httpRequest.class.php');
require('config.php');
require('functions.php');

if(!defined('wm_server_address')) {
  exit('wm_server_address is required in config.php');
}

if(caching) {
  if(!is_dir(cache_dir)) {
    if(!mkdir(cache_dir)) {
      exit('could not create cache directory');
    }
  }
}

$requestUrl = 'http://x-vids.eu/en/';
/*
$requestUrl = sprintf(
  'http%s://%s%s', (isset($_SERVER['HTTPS']) ? 's' : ''),
  $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI']
);
*/

list($rrBool, $rrData) = renderRequest($requestUrl);
if(!$rrBool) {
  exit($rrData);
}

list($contentType, $content) = $rrData;
header('Content-Type: '.$contentType);
print_r($content);
?>