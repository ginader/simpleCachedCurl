<?php
include 'simpleCachedCurl.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>simpleCachedCurl Example YQL call for twitter</title>
	<meta name="author" content="Dirk Ginader">
</head>
<body>
<?php

$url = 'http://query.yahooapis.com/v1/public/yql?q=select%20status.text%2C%20status.id%20from%20xml%20where%20url%20%3D%20%22http%3A%2F%2Ftwitter.com%2Fstatuses%2Fuser_timeline%2Fginader.xml%3Fcount%3D10%22&format=xml&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys';
$expires = 60; // 1 minute
$rawData = simpleCachedCurl($url,$expires);
echo "<pre>";print_r($rawData);echo"</pre>";

?>
</body>
</html>
