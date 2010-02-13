PHP simple cached cURL
======================
http://ginader.com

very simple wrapper for cURL that creates a local file cache

usage: created a folder named "cache" in the same folder as this file and chmod it 777
call this function with 2 parameters:
    $url (string) the URL of the data that you would like to load
    $expires (integer) the amound of seconds the cache should stay valid
    
returns either the raw cURL data or false if request fails and no cache is available
