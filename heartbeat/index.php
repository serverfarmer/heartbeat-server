<?php
// Server Farmer Heartbeat
//
// reference implementation, for shared hosting environments
// (without available memcached service)
//
// written by Tomasz Klim, 2017-2018



// Heartbeat sensitivity in seconds:  120 + 120 + 30
//
// (120 seconds between subsequent requests
//  + tolerate 1 failed request
//  + 30 seconds overall network lag)
//
// You may want to manipulate this parameter for particular
// $host / $id values inside foreach loop below.
//
$seconds = 270;



if (empty($_GET["host"]) || empty($_GET["services"]))
	die("missing parameters");

$ret1 = preg_match("/^([a-zA-Z0-9.\-]+)$/", $_GET["host"], $out1);
$ret2 = preg_match("/^([a-z0-9,]+)$/", $_GET["services"], $out2);

if (!$ret1 || !$ret2)
	die("missing parameters");

$host = str_replace(".", "_", $out1[0]);
$services = explode(",", $out2[0]);

$timeout = time() + $seconds;

foreach ($services as $service) {
	$id = $service."_".$host;
	$file = "files/$id.txt";
	file_put_contents($file, $timeout);
}

echo "ok\n";
