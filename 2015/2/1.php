<?php

$input = explode(PHP_EOL, file_get_contents(__DIR__ . '/input.txt'));

$total = 0;

foreach ($input as $line) {
    list($l, $w, $h)  = explode('x', $line);

    $dimensions = [
        $l * $w,
        $w * $h,
        $h * $l,
    ];

    // All dimensions are needed twice.
    $needed = 2 * array_sum($dimensions);

    // Find the smallest dimension to add for slack.
    sort($dimensions);
    $needed += array_shift($dimensions);

    $total += $needed;
}

echo "Amount of wrapping paper needed: " . $total;