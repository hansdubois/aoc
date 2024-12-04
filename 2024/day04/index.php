<?php
require __DIR__ . '/../common/Stopwatch.php';

$input = file_get_contents(__DIR__ . '/input.txt');

$stopwatch = new Stopwatch();
$stopwatch->start();

$grid = createGridForInput($input);

$wordCount = 0;

// |
$wordCount += findWordsInGrid($grid, "XMAS");

// /
$oneRotation = rotateArray45Degrees($grid);
$wordCount += findWordsInGrid($oneRotation, "XMAS");

// -
$twoRotations = rotateGrid($grid);
$wordCount += findWordsInGrid($twoRotations, "XMAS");

// \
$threeRotations = rotateArray45Degrees($twoRotations);
$wordCount += findWordsInGrid($threeRotations, "XMAS");

echo "Part 1: " . $wordCount . PHP_EOL;
echo $stopwatch->ellapsed() . PHP_EOL;

function createGridForInput(string $input): array {
    $grid = [];

    $lines = explode("\n", $input);

    for ($y = 0; $y < count($lines); $y++) {
        $grid[$y] = [];

        for ($x = 0; $x < strlen($lines[$y]); $x++) {
            $grid[$y][$x] = trim($lines[$y][$x]);
        }
    }

    return $grid;
}

function rotateArray45Degrees(array $grid) {
    // Get the number of rows (y) and columns (x) in the original array
    $rows = count($grid);
    $cols = count($grid[0]);

    // Initialize a rotated array
    $rotatedArray = [];

    // Iterate over each element in the original array
    for ($y = 0; $y < $rows; $y++) {
        for ($x = 0; $x < $cols; $x++) {
            // Calculate the new position in the rotated structure
            $newIndex = $x + $y; // Sum of x and y determines the new "diagonal level"

            if (!isset($rotatedArray[$newIndex])) {
                $rotatedArray[$newIndex] = [];
            }

            // Append the element to the new diagonal level
            $rotatedArray[$newIndex][] = $grid[$y][$x];
        }
    }

    return $rotatedArray;
}

function rotateGrid(array $grid): array {
    // Get the number of rows (y) and columns (x) in the original array
    $rows = count($grid);
    $cols = count($grid[0]);

    // Initialize the rotated array
    $rotatedArray = [];

    // Populate the rotated array
    for ($x = 0; $x < $cols; $x++) {
        $newRow = [];
        for ($y = $rows - 1; $y >= 0; $y--) {
            $newRow[] = $grid[$y][$x];
        }
        $rotatedArray[] = $newRow;
    }

    return $rotatedArray;
}

function findWordsInGrid(array $grid, string $word): int
{
    $count = 0;
    $reversed = strrev($word);

    for ($y = 0; $y < count($grid); $y++) {
        $line = join("", $grid[$y]);

        $count += substr_count($line, $word);
        $count += substr_count($line, $reversed);
    }

    return $count;
}

function printGrid(array $grid) {
    for ($y = 0; $y < count($grid); $y++) {
        for ($x = 0; $x < count($grid[$y]); $x++) {
            echo $grid[$y][$x];
        }

        echo PHP_EOL;
    }
}

function printSpaced(array $grid)
{
    if (count($grid[0]) > 2) {
        foreach ($grid as $elements) {
            echo implode(' ', $elements) . "\n";
        }
    } else {
        // Print the rotated array as a diamond-like structure
        $maxWidth = 0;

        foreach ($grid as $level => $elements) {
            if (count($elements) > $maxWidth) {
                $maxWidth = count($elements);
            }
        }

        foreach ($grid as $level => $elements) {
            if($level < count($grid) / 2) {
                echo str_repeat(' ', $maxWidth - $level - 1); // Add spacing for alignment
            } else {
                echo str_repeat(' ',  $level - $maxWidth + 1); // Add spacing for alignment
            }

            echo implode(' ', $elements) . "\n";
        }


    }
}