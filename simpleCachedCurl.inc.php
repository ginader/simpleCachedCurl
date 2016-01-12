<?php
/*
simpleCachedCurl V1.1

Dirk Ginader
http://ginader.com

Copyright (c) 2013 Dirk Ginader

Dual licensed under the MIT and GPL licenses:
http://www.opensource.org/licenses/mit-license.php
http://www.gnu.org/licenses/gpl.html 

code: http://github.com/ginader

easy to use cURL wrapper with added file cache

usage: created a folder named "cache" in the same folder as this file and chmod it 777
call this function with 3 parameters:
    $url (string) the URL of the data that you would like to load
    $expires (integer) the amound of seconds the cache should stay valid
    $curlopt (array, optional) set multiple options for a cURL transfer http://php.net/manual/en/function.curl-setopt-array.php
    $debug (boolean, optional) write debug information for troubleshooting
    
returns either the raw cURL data or false if request fails and no cache is available

*/
function simpleCachedCurl($url,$expires,$curlopt = array(),$debug=false){
    if($debug){
        echo "simpleCachedCurl debug:<br>";
    }
    $hash = md5($url);
    $filename = dirname(__FILE__).'/cache/' . $hash . '.cache';
    $changed = file_exists($filename) ? filemtime($filename) : 0;
    $now = time();
    $diff = $now - $changed;   
    if ( !$changed || ($diff > $expires) ) {
        if($debug){
            echo "no cache or expired --> make new request<br>";
        }
        $ch = curl_init();
        $default_curlopt = array(
            CURLOPT_TIMEOUT => 2,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FOLLOWLOCATION => 1
        );
        $curlopt = array(CURLOPT_URL => $url) + $curlopt + $default_curlopt;
        curl_setopt_array($ch, $curlopt);
        $rawData = curl_exec($ch);
        curl_close($ch);
        if(!$rawData){
            if($debug){
                echo "cURL request failed<br>";
            }
            if($changed){
                if($debug){
                    echo "at least we have an expired cache --> better than nothing --> read it<br>";
                }
                $cache = unserialize(file_get_contents($filename));
                return $cache;
            }else{
                if($debug){
                    echo "request failed and we have no cache at all --> FAIL<br>";
                }
                return false;
            }
        }
        if($debug){
            echo "we got a return --> save it to cache<br>";
        }
        $cache = fopen($filename, 'wb');
        $write = fwrite($cache, serialize($rawData));
        if($debug && !$write){
            echo "writing to $filename failed. Make the folder '".dirname(__FILE__).'/cache/'."' is writeable (chmod 777)<br>";
        }
        fclose($cache);
        return $rawData;
    }
    if($debug){
        echo "yay we hit the cache --> read it<br>";
    }
    $cache = unserialize(file_get_contents($filename));
    return $cache;
}
?>
