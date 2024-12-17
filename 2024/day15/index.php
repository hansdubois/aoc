<?php
declare(strict_types = 1);

require __DIR__ . '/../common/Coord.php';
require __DIR__ . '/../common/Grid.php';

const FLOOR = ".";
const BOX = "O";

const WALL = "#";

const ROBIT = "@";

$input = explode("\n", file_get_contents(__DIR__. '/input_warehouse.txt'));
$movement = array_map(function (string $command) {
    switch ($command) {
        case "^":
            return UP;
        case "<":
            return LEFT;
        case "v":
            return DOWN;
        case ">":
            return RIGHT;
    }
}, str_split(str_replace("\n", "", file_get_contents(__DIR__. '/input_movement.txt'))));

$grid = new Grid();
$robit = null;

for($y = 0; $y < count($input); $y++) {
    $charList = str_split($input[$y]);

    for ($x = 0; $x < count($charList); $x++) {
        $grid->add(new Coord($x, $y), $charList[$x]);

        if ($charList[$x] == '@') {
            $robit = new Coord($x, $y);
        }
    }
}

$grid->print();

/** @var Coord $move */
foreach ($movement as $move) {
    $newPosition = $robit->add($move);

    if ($grid->existsOnGrid($newPosition)) {
        $target = $grid->get($newPosition->x, $newPosition->y);

        if ($target != WALL) {
            if ($target != BOX) {
                $grid->add($robit, FLOOR);

                $robit = $newPosition;

                $grid->add($robit, ROBIT);
            } elseif (moveBoxes($newPosition, $move, $grid)) {
                $grid->add($robit, FLOOR);

                $robit = $newPosition;

                $grid->add($robit, ROBIT);
            }
        }
    }

//    $grid->print();
//    echo PHP_EOL;
}

$part1 = 0;

$allBoxes = $grid->getAllItemsThatHaveValue(BOX);

/** @var Coord $box */
foreach ($allBoxes as $box) {
    $part1 += (100 * $box->y) + $box->x;
}

echo "Part 1: " . $part1 . PHP_EOL;

function moveBoxes(Coord $box, Coord $direction, Grid $grid): bool
{
    // Get all boxes in direction until floor or wall
    $checking = $box;

    $foundWall = false;
    $foundFloor = false;
    $boxesToMove = [];

    while($grid->existsOnGrid($checking) && !$foundWall && !$foundFloor) {
        $willMoveTo = $checking->add($direction);

        $willMoveToType = $grid->get($willMoveTo->x, $willMoveTo->y);

        if ($willMoveToType == WALL) {
            $foundWall = true;
        } elseif ($willMoveToType == FLOOR) {
            $boxesToMove[] = $willMoveTo;
            $checking = $willMoveTo;

            $foundFloor = true;
        } else {
            $boxesToMove[] = $willMoveTo;
            $checking = $willMoveTo;
        }
    }

    if ($foundWall) {
        return false;
    }

    if ($foundFloor) {
        //echo "I will move: " . count($boxesToMove) . " boxes" . PHP_EOL;

        if (count($boxesToMove) > 0) {
            foreach ($boxesToMove as $box) {
                $grid->add($box, BOX);
            }
        }

        return true;
    }

    return false;

}