#!/usr/bin/php
<?php

/* Gather MogileFS Trackers stats 
 * by Andre Pascha <bender@duese.org>
 * on 12.01.2011
 */

if(!$argv[1]) {
        die("Please provide a hostname!\n");
}

$host = $argv[1];
$port = 7001;

$ret = array(
		'uptime' => 0,
		'pending_queries' => 0,
		'processing_queries' => 0,
		'bored_queryworkers' => 0,
		'queries' => 0,
		'work_queue_for_delete' => 0,
		'work_queue_for_replicate' => 0,
		'work_sent_to_delete' => 0,
		'work_sent_to_replicate' => 0,
);

$s = fsockopen($host, $port);
if(!$s)
	die("eror connecting!!\n");

fwrite($s, "!stats\r\n");

$c = 0;
do {
	$line = trim(fgets($s));
	if($line != ".") {
		list($key, $value) = explode(" ", $line);
		if (isset($ret[$key])) $ret[$key] = $value;
	}
	$c++;
}
while($line != "." && $c < 100);

fclose($s);

$out = array();

foreach($ret as $key => $value) {
	$out[] = $key.":".$value;
}

echo implode(" ", $out);

?>
