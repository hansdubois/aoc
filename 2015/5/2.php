<?php

$input = explode(PHP_EOL, file_get_contents(__DIR__ . '/input.txt'));

$niceCount = 0;

foreach ($input as $item) {
    if (preg_match('/(..).*\1/', $item) && preg_match('/(.).\1/', $item)) {
        $niceCount++;
    };
}

var_dump($niceCount);