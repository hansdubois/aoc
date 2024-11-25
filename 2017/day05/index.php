<?php
$input = "0
3
0
1
-3";

$instructions = array_map('intval', explode("\n", $input));
$done = false;
$index = 0;
$itt = 0;

while(!$done)
{
    $stepsToTake = $instructions[$index];

    $nextIndex = $index + $stepsToTake;

    if ($stepsToTake >= 3) {
        $instructions[$index] = $instructions[$index] - 1;
    } else {
        $instructions[$index] = $instructions[$index] + 1;
    }

    $itt++;

    if ($nextIndex >= count($instructions)) {
        $done = true;
    }

    $index = $nextIndex;
}



echo "Part 1: " . $itt;