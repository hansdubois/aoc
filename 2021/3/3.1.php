<?php

declare(strict_types=1);

$input = explode(PHP_EOL, file_get_contents(__DIR__ . '/input.txt'));
$keyLength = 12;

$dataPoints = [
    0 => [0 => 0, 1 => 0],
    1 => [0 => 0, 1 => 0],
    2 => [0 => 0, 1 => 0],
    3 => [0 => 0, 1 => 0],
    4 => [0 => 0, 1 => 0],
    5 => [0 => 0, 1 => 0],
    6 => [0 => 0, 1 => 0],
    7 => [0 => 0, 1 => 0],
    8 => [0 => 0, 1 => 0],
    9 => [0 => 0, 1 => 0],
    10 => [0 => 0, 1 => 0],
    11 => [0 => 0, 1 => 0]
];

foreach ($input as $line) {
    for ($i = 0; $i < strlen($line); $i++) {
        $char = substr($line, $i, 1);

        $char === '1' ? $dataPoints[$i][1]++ : $dataPoints[$i][0]++;
    }
}

$gamma = '';
$epsilon = '';

foreach ($dataPoints as $dataPoint) {
    if ($dataPoint[0] > $dataPoint[1])
    {
        $gamma .= '0';
    } else {
        $gamma .= '1';
    }

    if ($dataPoint[0] < $dataPoint[1])
    {
        $epsilon .= '0';
    } else {
        $epsilon .= '1';
    }
}

echo bindec($gamma) . PHP_EOL;
echo bindec($epsilon) . PHP_EOL;

//313131313030313030303131

//var_dump($dataPoints);