<?php
// Server Farmer Heartbeat
//
// reference implementation, for shared hosting environments
// (without available memcached service)
//
// written by Tomasz Klim, 2017-2018



if (empty($_GET["id"]))
	die("missing parameters");

$ret = preg_match("/^([a-zA-Z0-9_\-]+)$/", $_GET["id"], $out);

if (!$ret)
	die("missing parameters");

$id = $out[0];
$file = "files/$id.txt";

if (!file_exists($file))
	die("DEAD");

$timeout = (int)file_get_contents($file);
die($timeout > time() ? "ALIVE:$timeout" : "DEAD");
