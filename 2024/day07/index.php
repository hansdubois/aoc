<?php

$lines = explode("\n", file_get_contents(__DIR__ . '/input.txt'));
$count = 0;
$part2count = 0;

require __DIR__ . '/../common/Stopwatch.php';

$stopwatch = new Stopwatch();
$stopwatch->start();

foreach ($lines as $line) {
    [$testValue, $operators] = explode(":", $line);

    if (tryAllOptions(array_map('intval', explode(" ", trim($operators))), $testValue, 0)) {
        $count += $testValue;
    }

    if (tryAllOptions(array_map('intval', explode(" ", trim($operators))), $testValue, 0, true)) {
        $part2count += $testValue;
    }
}

echo "Part 1: " . $count . "\n";
echo "Part 2: " . $part2count . "\n";

echo $stopwatch->ellapsed() . PHP_EOL;

function tryAllOptions(
    array $operators,
    int $testValue,
    int $prev,
    ?bool $partTwo = false
): bool {
    if ($prev > $testValue) {
        return false;
    }

    // Done!
    if (empty($operators)) {
        return $prev === $testValue;
    }

    $number = array_shift($operators);
    if (tryAllOptions($operators, $testValue, $prev + $number, $partTwo)) {
        return true;
    }

    if (tryAllOptions($operators, $testValue, $prev * $number, $partTwo)) {
        return true;
    }

    if ($partTwo && tryAllOptions($operators, $testValue, (int) ( $prev . $number ), true)) {
        return true;
    }

    return false;
}