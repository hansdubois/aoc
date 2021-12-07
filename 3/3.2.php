<?php

declare(strict_types=1);

$input = explode(PHP_EOL, file_get_contents(__DIR__ . '/input.txt'));
$mostValue = filterMost($input);
$leastValue = filterLeast($input);

var_dump(bindec($mostValue) * bindec($leastValue));

function filterMost(array $values, int $position = 0): string {
    if (count($values) === 1) {
        return reset($values);
    }

    $ones = [];
    $zeros = [];

    foreach ($values as $value) {
        substr($value, $position, 1) === '1'  ? $ones[] = $value : $zeros[] = $value;
    }

    if (count($ones) > count($zeros) || count($ones) == count($zeros)) {
        return filterMost($ones, $position + 1);
    }

    return filterMost($zeros, $position + 1);
}

function filterLeast(array $values, int $position = 0): string {
    if (count($values) === 1) {
        return reset($values);
    }

    $ones = [];
    $zeros = [];

    foreach ($values as $value) {
        substr($value, $position, 1) === '1'  ? $ones[] = $value : $zeros[] = $value;
    }

    echo "ones: " . count($ones) . " zeros: " . count($zeros) . " Position: " . $position . PHP_EOL;

    if (count($zeros) < count($ones) || count($ones) == count($zeros)) {
        return filterLeast($zeros, $position + 1);
    }

    return filterLeast($ones, $position + 1);
}
