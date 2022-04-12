<?php

$input = explode(PHP_EOL, file_get_contents(__DIR__ . '/input.txt'));
//$input = ["2x3x4"];

$total = 0;

foreach ($input as $line) {
    list($l, $w, $h)  = explode('x', $line);

    $dimensions = [
        $l,
        $w,
        $h,
    ];

    sort($dimensions);


    // The ribbon needs to go on the two shortest distances.
    $length = ($dimensions[0] + $dimensions[1]) * 2;

    // The bow needs all dimensions multiplied.
    $total += $length + array_product($dimensions);

}

echo "Amount of ribbon needed: " . $total;