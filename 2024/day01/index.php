<?php

$input = explode("\n", file_get_contents(__DIR__ . '/input.txt'));

$left = $right = [];

foreach ($input as $line) {
    $parts = explode('   ', $line);

    $left[] = intval($parts[0]);
    $right[] = intval($parts[1]);
}

sort($left);
sort($right);

$valueCounts = array_count_values($right);

$totalDistance = 0;
$similarityScore = 0;

for ($i = 0; $i < count($left); $i++) {
    $totalDistance += abs($left[$i] - $right[$i]);

    if (array_key_exists($left[$i], $valueCounts)) {
        $similarityScore += $left[$i] * $valueCounts[$left[$i]];
    }
}

echo "Part 1: " . $totalDistance . PHP_EOL;
echo "Part 2: " . $similarityScore . PHP_EOL;