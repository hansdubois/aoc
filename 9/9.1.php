<?php
declare(strict_types=1);

$lines = explode(PHP_EOL, file_get_contents(__DIR__ . '/input.txt'));

$map = array_fill(0, count($lines), []);

foreach ($lines as $i => $line) {
    $map[$i]  = array_map('intval', str_split($line));
}

$lowPoints = [];

for ($line = 0; $line < count($map); $line++)
{
    for ($position = 0; $position < count($map[$line]); $position++) {
        $valueToCheck = $map[$line][$position];

        $checkedPositions = 0;
        $lowPointCount = 0;

        // left
        if ($position > 0) {
            $checkedPositions++;

            if ($map[$line][$position - 1 ] > $valueToCheck) {
                $lowPointCount++;
            }
        }

        // top
        if ($line > 0) {
            $checkedPositions++;

            if ($map[$line -1][$position] > $valueToCheck) {
                $lowPointCount++;
            }
        }

        // bottom
        if ($line != (count($lines) -1)) {
            $checkedPositions++;

            if ($map[$line + 1][$position] > $valueToCheck) {
                $lowPointCount++;
            }
        }

        // right
        if ($position < (count($map[$line]) -1)) {
            $checkedPositions++;

            if ($map[$line][$position +1] > $valueToCheck) {
                $lowPointCount++;
            }
        }
        if ($lowPointCount === $checkedPositions) {
            $lowPoints[] = $valueToCheck + 1;
        }
    }
}

echo array_sum($lowPoints);
