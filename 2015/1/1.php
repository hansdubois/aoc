<?php

$input = file_get_contents(__DIR__ . '/input.txt');

$up = substr_count($input, '(');
$down = substr_count($input, ')');

echo $up - $down;