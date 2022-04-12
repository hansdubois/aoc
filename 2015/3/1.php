<?php

$input = file_get_contents(__DIR__ . '/input.txt');

$x = 0;
$y = 0;

$coords = [];

for ($i = 0; $i < strlen($input); $i++) {
    switch ($input[$i]) {
        case "<":
            $x -= 1;
            break;

        case "^":
            $y += 1;
            break;

        case ">":
            $x += 1;
            break;

        case "v":
            $y -= 1;
            break;
    }

    $coords[] = "$x$y";
}

echo "Total visits: " . count($coords) . PHP_EOL;
echo "Unique visits: " . count(array_unique($coords)) . PHP_EOL;