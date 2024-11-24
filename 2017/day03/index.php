<?php
require_once  __DIR__ . '/../base/grid.php';
require_once __DIR__ . '/../base/coord.php';

$input = 265149;

$total = ceil(sqrt($input));
$middle = ceil(($total -1) / 2);

echo "Part 1: " . max($middle - 1 + abs($middle - $input % $total), 0) . PHP_EOL;

$steps = [
    RIGHT,
    UP,
    LEFT,
    DOWN
];

$grid = new Grid();

$directionIndicator = 0;
$totalStepsCounter = 1;
$stepsToTake = 1;
$startNumber = 1;
$total = 0;
$found = false;
$max = 265149;

$itt = 0;

$stepSize = 1;
$stepss = 0;
$stepsTaken = 0;

$currentPosition = new Coord(0,0);
$grid->add($currentPosition, 1);

//for ($i = 0; $i < 10; $i++) {
while (!$found) {
    echo "X:", $currentPosition->x, "Y:", $currentPosition->y, "VALUE:", $grid->get($currentPosition->x, $currentPosition->y);

    $direction = $steps[$directionIndicator];

    $nextPosition = $currentPosition->add($direction);

    $surrounding = $grid->returnAllSurroundingValues($nextPosition);

    $newNumber = array_sum($surrounding);

    $grid->add($nextPosition, $newNumber);
    $currentPosition = $nextPosition;

    $stepsTaken++;

    if ($stepsTaken == $stepSize) {
        $directionIndicator++;
        $stepss++;

        if ($directionIndicator == 4) {
            $directionIndicator = 0;
        }

        $stepsTaken = 0;

        if ($stepss == 2) {
            $stepss = 0;
            $stepSize++;
        }
    }

    echo PHP_EOL;

    $totalStepsCounter++;

    if ($newNumber > $max) {
        echo $newNumber--;

        $found = true;
    }
}

$grid->print();