<?php

$memory = file_get_contents(__DIR__ . '/input.txt');

$regexPartTwo = "/do\(\)(.*?)don't\(\)/";
preg_match_all($regexPartTwo, "do()" . $memory . "don't()", $matches2, PREG_SET_ORDER, 0);

$part2Total = 0;
foreach ($matches2 as $match) {
    $part2Total += score($match[0]);
}


echo "Part 1: " . score($memory) . PHP_EOL;
echo "Part 2: " . $part2Total . PHP_EOL;


function score(string $memory) {
    $regex = "/mul\((\d{1,3}),(\d{1,3})\)/";

    preg_match_all($regex, $memory, $matches, PREG_SET_ORDER, 0);

    $total = 0;

    foreach ($matches as $match) {
        $total += intval($match[1]) * intval($match[2]);
    }

    return $total;
}