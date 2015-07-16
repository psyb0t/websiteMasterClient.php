<?php
function renderRequest($url) {
  if(caching) {
    $cache = readCache($url);
    if($cache) {
      list($contentType, $content) = $cache;
      return [true, [$contentType, base64_decode($content)]];
    }
  }
  
  list($rBool, $rData) = doRequest($url);
  if(!$rBool) {
    return [false, $rData];
  }
  
  if(caching) {
    if(!writeCache($url, $rData)) {
      error_log('could not write to cache');
    }
  }
  
  list($contentType, $content) = $rData;
  return [true, [$contentType, base64_decode($content)]];
}

function doRequest($url) {
  $request = new httpRequest(wm_server_address);
  $request->setUserAgent('websiteMasterClient.php');
  $request->setTimeout(60);

  $postData = json_encode([
    'act' => 'render',
    'data' => [
      'url' => $url
    ]
  ]);
  
  $request->setPOST($postData);
  $request = $request->exec();
  
  if($request['status'] != 'OK') {
    return [false, $request['message']];
  }

  $reqData = json_decode($request['data'], true);
  if(!$reqData) {
    return [false, 'Invalid response data'];
  }
  
  if($reqData['status'] != 'OK') {
    return [false, $reqData['message']];
  }
  
  $contentType = $reqData['content_type'];
  $content = $reqData['data'];
  
  return [true, [$contentType, $content]];
}

function readCache($url) {
  $cacheFile = cache_dir.sha1($url);
  if(!file_exists($cacheFile)) {
    return false;
  }
  
  $cacheData = file_get_contents($cacheFile);
  $cacheData = json_decode($cacheData, true);
  if(!$cacheData) {
    return false;
  }
  
  if($cacheData['timestamp'] < (time() - cache_life)) {
    return false;
  }
  
  return $cacheData['data'];
}

function writeCache($url, $data) {
  $cacheFile = cache_dir.sha1($url);
  
  $fileHandler = fopen($cacheFile, 'w');
  if(!$fileHandler) {
    return false;
  }
  
  $cacheData = [
    'timestamp' => time(),
    'data' => $data
  ];
  
  $return = true;
  if(!fwrite($fileHandler, json_encode($cacheData))) {
    $return = false;
  }
  fclose($fileHandler);
  
  return $return;
}
?>