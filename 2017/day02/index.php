<?php
$input = "5 9 2 8
9 4 7 3
3 8 6 5";

$rows = explode("\n", $input);

$diff = 0;

foreach ($rows as $row) {
    $numbers = array_map('intval', explode(" ", $row));

    sort($numbers);

    $diff += array_pop($numbers) - array_shift($numbers);
}

$count = 0;

foreach ($rows as $row) {
    $numbers = array_map('intval', explode(" ", $row));

    for ($i = 0; $i < count($numbers); $i++) {
        for ($j = 0; $j < count($numbers); $j++) {
            if ($i != $j) {
                if ($numbers[$i] % $numbers[$j] === 0) {
                    $count += $numbers[$i] / $numbers[$j];
                }
            }
        }
    }
}

echo "Part 1: " . $diff . PHP_EOL;
echo "Part 2: " . $count . PHP_EOL;




