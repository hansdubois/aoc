<?php
require __DIR__ . '/../common/Stopwatch.php';

$stopwatch = new Stopwatch();

$lines = explode("\n", file_get_contents(__DIR__ . '/input.txt'));

$safeCount = 0;
$partTwoSafeCount = 0;

$stopwatch->start();
foreach ($lines as $line) {
    if (isNumberSafePartOne($line)) {
        $safeCount++;
    }

    if (isNumberSafePartTwo($line)) {
        $partTwoSafeCount++;
    }
}

$time = $stopwatch->ellapsedMS();

function isNumberSafePartOne(string $number): bool {
    $numbers = array_map('intval', explode(" ", $number));

    $asc = $desc = $numbers;

    sort($asc);
    rsort($desc);

    echo join( "-", $numbers) . "=> " ;

    if ($asc === $numbers || $desc === $numbers) {
        $safe = true;

        for ($i = 0; $i < count($numbers); $i++) {
            if ($i != count($numbers) - 1) {
                $diff = abs($numbers[$i] - $numbers[$i + 1]);

                if ($diff > 3 || $diff === 0) {
                    $safe = false;
                }
            }
        }

        if ($safe) {
            echo "SAFE";
            echo PHP_EOL;

            return true;
        } else {
            echo "NOT SAFE";
            echo PHP_EOL;

            return false;
        }

    } else {
        echo "Numbers are not in order" . PHP_EOL;

        return false;
    }
}

function isNumberSafePartTwo(string $number): bool {
    $numbers = array_map('intval', explode(" ", $number));

    // calculate all possible options
    for ($i = 0; $i < count($numbers); $i++) {
        $copy = $numbers;
        unset($copy[$i]);

        echo join( "-", $copy) . "=>" . PHP_EOL;

        if (isNumberSafePartOne(join( " ", $copy))) {
            return true;
        }
    }

    return false;
}

echo PHP_EOL;
echo "Part 1: " . $safeCount . PHP_EOL;
echo "Part 2: " . $partTwoSafeCount . PHP_EOL;

echo $time . PHP_EOL;
