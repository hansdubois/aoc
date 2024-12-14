<?php

$input = explode("\n", file_get_contents(__DIR__ . '/input.txt'));
$movementTimes = 100;

$width = 101;
$height = 103;

$horizontalLine = intval(ceil($height / 2)) -1;
$verticalLine = intval(ceil($width / 2 )) -1;

$quadrants = [
    'A' => ['x' => ['from' => 0, 'to' => $verticalLine -1,], 'y' => ['from' => 0, 'to' => $horizontalLine -1]],
    'B' => ['x' => ['from' => $verticalLine + 1, 'to' => $width -1,], 'y' => ['from' => 0, 'to' =>$horizontalLine -1]],
    'C' => ['x' => ['from' => 0, 'to' => $verticalLine -1,], 'y' => ['from' => $horizontalLine + 1, 'to' => $height -1]],
    'D' => ['x' => ['from' => $verticalLine + 1, 'to' => $width -1,], 'y' => ['from' => $horizontalLine + 1, 'to' => $height -1]],
];

$quadrantCount = [
    'A' => 0,
    'B' => 0,
    'C' => 0,
    'D' => 0,
];

$positions = [];

foreach ($input as $line) {
    [$position, $velocity] = explode(' ', $line);

    $position = str_replace("p=", "", $position);
    [$positionX, $positionY] = explode(',', $position);

    $velocity = str_replace("v=", "", $velocity);
    [$velocityX, $velocityY] = explode(',', $velocity);

    $movement = ['x' => $velocityX * $movementTimes, 'y' => $velocityY * $movementTimes];

    $movementX = $movement['x'] % $width;
    $movementY = $movement['y'] % $height;

    $positionX += $movementX;
    $positionY += $movementY;

    if ($positionX >= $width) {
        $positionX -= $width;
    } elseif ($positionX < 0) {
        $positionX += $width;
    }

    if ($positionY >= $height) {
        $positionY -= $height;
    } elseif ($positionY < 0) {
        $positionY += $height;
    }

    $key = $positionX . '-' . $positionY;
    if(!array_key_exists($key, $positions)) {
        $positions[$key] = 0;
    }

    $positions[$key]++;

    foreach ($quadrants as $quadrantName => $quadrant) {
        if ($positionX >= $quadrant['x']['from'] && $positionX <= $quadrant['x']['to']) {
            if ($positionY >= $quadrant['y']['from'] && $positionY <= $quadrant['y']['to']) {
                $quadrantCount[$quadrantName]++;
            }
        }
    }

    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            $key = $x . "-" . $y;

            if ($y === $horizontalLine || $x === $verticalLine) {
                echo " ";
            }elseif (array_key_exists($x . "-" . $y, $positions)) {
                echo $positions[$key];
            } else {
                echo ".";
            }
        }

        echo PHP_EOL;
    }
}

echo "Part one: ". array_product($quadrantCount) . "\n";