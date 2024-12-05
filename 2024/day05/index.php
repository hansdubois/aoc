<?php
ini_set("memory_limit","-1");


$lines = explode("\n", file_get_contents(__DIR__ . '/input.txt'));
$updates = explode("\n", file_get_contents(__DIR__ . '/input_updates.txt'));

$order = [];

require __DIR__ . '/../common/Stopwatch.php';

foreach ($lines as $line) {
    $parts = explode('|', $line);

    $order[$parts[0]][] = $parts[1];
}

$stopwatch = new Stopwatch();
$stopwatch->start();

$partOne = 0;
$partTwo = 0;

foreach ($updates as $update) {
    $parts = explode(',', $update);
    $sort = sortLine($parts, $order);

    if($parts === $sort) {
        $middleItem = (count($parts) - 1) / 2;
        $partOne += intval($parts[$middleItem]);
    } else {
        $middleItem = (count($sort) - 1) / 2;
        $partTwo += intval($sort[$middleItem]);
    }
}

echo "Part 1: " . $partOne . PHP_EOL;
echo "Part 2: " . $partTwo . PHP_EOL;

echo $stopwatch->ellapsed() . PHP_EOL;

function sortLine(array $line, array $order): array
{
    usort($line, function ($a, $b) use ($order) {
        return in_array($a, $order[$b]) <=> in_array($b,  $order[$a]);
    });

    return $line;
}