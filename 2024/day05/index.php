<?php
$lines = explode("\n", file_get_contents(__DIR__ . '/input.txt'));
$updates = explode("\n", file_get_contents(__DIR__ . '/input_updates.txt'));

require __DIR__ . '/../common/Stopwatch.php';

foreach ($lines as $line) {
    [$main, $dependency] = explode('|', $line);

    $order[$main][] = $dependency;
}

$stopwatch = new Stopwatch();
$stopwatch->start();

$partOne = 0;
$partTwo = 0;

/**
 * Build a list <Page, NeedsToBeBefore[]>
 *
 * Sort the update based on the list
 * Compare to the original, if same Part 1, if not Part 2.
 */

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