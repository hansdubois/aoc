<?php

$input = explode(PHP_EOL.PHP_EOL, file_get_contents(__DIR__. '/input.txt'));

$keys = [];
$locks = [];

foreach ($input as $item) {
    $lines = explode("\n", $item);

    $itemGrid = [];

    for ($y = 0; $y < count($lines); $y++) {
        $itemGrid[$y] = [];

        $chars = str_split($lines[$y]);

        for ($x = 0; $x < count($chars); $x++) {
            $itemGrid[$y][$x] = $chars[$x];
        }
    }

    $clockwise = rotateArrayClockwise($itemGrid);
    $itemCode = calculateItemCode($clockwise);

    if ($lines[0][0] === "#") {
        $keys[] = $itemCode;
    } else {
        $locks[] = $itemCode;
    }
}

$part1 = 0;

foreach ($keys as $key) {
    foreach ($locks as $lock) {
        if (doesFit($lock, $key)) {
            $part1++;
        }
    }
}

echo "Part 1: " . $part1 . PHP_EOL;

function doesFit($lock, $key){
    $combination = array_map(function (...$arrays) {
        return array_sum($arrays);
    }, $key, $lock);

    foreach ($combination as $sum) {
        if ($sum > 7) {
            return false;
        }
    }

    return true;
}

function calculateItemCode(array $item): array {
    return array_map(function ($line) {
        $values = array_count_values($line);

        if (array_key_exists("#", $values)) {
            return $values["#"];
        }

        return 0;
    }, $item);
}

function rotateArrayClockwise($array) {
    $rotatedArray = [];
    $rows = count($array);
    $cols = count($array[0]);

    for ($i = 0; $i < $cols; $i++) {
        $newRow = [];
        for ($j = $rows - 1; $j >= 0; $j--) {
            $newRow[] = $array[$j][$i];
        }
        $rotatedArray[] = $newRow;
    }

    return $rotatedArray;
}
