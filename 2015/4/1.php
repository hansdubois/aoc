<?php

$secret = "bgvyzdsv";
$count = 0;

$hash = "";
do {
    $hash = md5($secret . $count);

    echo $hash . "----"  . $count . PHP_EOL;

    $count++;


} while (strpos($hash, "000000") !== 0);