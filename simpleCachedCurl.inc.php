<?php
/*
simpleCachedCurl V1.0
Dirk Ginader
ginader.com

code: http://github.com/ginader

easy to use cURL wrapper with added file cache

usage: created a folder named "cache" in the same folder as this file and chmod it 777
call this function with 2 parameters:
    $url (string) the URL of the data that you would like to load
    $expires (integer) the amound of seconds the cache should stay valid
    
returns either the raw cURL data or false if request fails and no cache is available

*/
function simpleCachedCurl($url,$expires){
    $hash = md5($url);
    $filename = dirname(__FILE__).'/cache/' . $hash . '.cache';
    $changed = filemtime($filename);
    $now = time();
    $diff = $now - $changed;    
    if ( !$changed || ($diff > $expires) ) {
        // no cache or expired --> make new request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $rawData = curl_exec($ch);
        curl_close($ch);
        if(!$rawData){
            // request failed and we have no cache --> fail
            return false;
        }
        // we got a return --> save it to cache
        $cache = fopen($filename, 'wb');
        fwrite($cache, serialize($rawData));
        fclose($cache);
        return $rawData;
    }
    // yay we hit the cache --> read it
    $cache = unserialize(file_get_contents($filename));
    return $cache;
}
?>