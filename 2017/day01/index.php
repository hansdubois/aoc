<?php

$input = "1111";

$splittedInput = str_split($input);

array_walk($splittedInput, function (&$value) { $value = intval($value); });

var_dump($splittedInput);

$count = 0;

for ($i = 0; $i < count($splittedInput); $i++) {
    $compareIndex = $i +1;

    if ($i + 1 == count($splittedInput)) {
        $compareIndex = 0;
    }

    if ($splittedInput[$i] == $splittedInput[$compareIndex]) {
        $count += $splittedInput[$i];
    }
}

echo "Part 1: " . $count;

$part2 = 0;
$lookAhead = count($splittedInput) / 2;

for ($i = 0; $i < count($splittedInput); $i++) {
    $compareIndex = $i + $lookAhead;

    if ($i + $lookAhead >= count($splittedInput)) {
        $compareIndex = $i + $lookAhead - count($splittedInput);
    }

    if ($splittedInput[$i] == $splittedInput[$compareIndex]) {
        $part2 += $splittedInput[$i];
    }
}

echo "Part 2: " . $part2;