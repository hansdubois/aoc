<?php
declare(strict_types = 1);

require __DIR__ . '/../common/Coord.php';
require __DIR__ . '/../common/Grid.php';

const FLOOR = ".";
const BOX_LEFT = "[";
const BOX_RIGHT = "]";

const WALL = "#";

const ROBIT = "@";



class Box {
    public function __construct(
        public readonly Coord $left,
        public readonly Coord $right,
    )
    {}

    public function __toString(): string
    {
        return implode('-', [$this->left->x, $this->left->y, $this->right->x, $this->right->y]);
    }
}



$input = explode("\n", str_replace(
    ["O", "#", ".", "@"],
    ["[]", "##", "..", "@."],
    file_get_contents(__DIR__. '/input_warehouse.txt')));

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

foreach ($movement as $move) {
    $newPosition = $robit->add($move);

    if ($grid->existsOnGrid($newPosition)) {
        $target = $grid->get($newPosition->x, $newPosition->y);

        if ($target != WALL) {
            if ($target != BOX_LEFT && $target != BOX_RIGHT ) {
                $grid->add($robit, FLOOR);

                $robit = $newPosition;

                $grid->add($robit, ROBIT);
            } elseif (($move == RIGHT || $move == LEFT) && moveBoxes($newPosition, $move, $grid)) {
                $grid->add($robit, FLOOR);

                $robit = $newPosition;

                $grid->add($robit, ROBIT);
            } elseif (($move == UP || $move == DOWN) && moveWideBoxes($newPosition, $move, $grid)) {
                $grid->add($robit, FLOOR);

                $robit = $newPosition;

                $grid->add($robit, ROBIT);
            }
        }
    }
}

$part1 = 0;

$grid->print();
$allBoxes = $grid->getAllItemsThatHaveValue(BOX_LEFT);

/** @var Coord $box */
foreach ($allBoxes as $box) {
    $part1 += (100 * $box->y) + $box->x;
}

echo "Part 2: " . $part1 . PHP_EOL;

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
            $boxesToMove[] = ["coord" => $willMoveTo, "value" => $grid->get($checking->x, $checking->y)];
            $checking = $willMoveTo;

            $foundFloor = true;
        } else {
            $boxesToMove[] = ["coord" => $willMoveTo, "value" => $grid->get($checking->x, $checking->y)];
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
                $grid->add($box["coord"], $box["value"]);
            }
        }

        return true;
    }

    return false;
}


/** @var Coord $move */
function moveWideBoxes(Coord $boxPart, Coord $move, Grid $grid)
{
    // Get counterpart
    $part = $grid->get($boxPart->x, $boxPart->y);

    if ($part == BOX_LEFT) {
        $box = new Box($boxPart, $boxPart->add(RIGHT));
    } else {
        $box = new Box($boxPart->add(LEFT), $boxPart);
    }

    // find other boxes in direction
    $queue = [$box];
    $checked = [];
    $foundObstruction = false;
    $toBeMoved = [];

    while ($queue && !$foundObstruction) {
        $box = array_shift($queue);

        if (array_key_exists((string)$box, $checked)) {
            break;
        }

        $leftNextCoord = $box->left->add($move);
        $rightNextCoord = $box->right->add($move);

        $leftNextValue = $grid->get($leftNextCoord->x, $leftNextCoord->y);
        $rightNextValue = $grid->get($rightNextCoord->x, $rightNextCoord->y);

        echo $leftNextValue . "-" . $rightNextValue . PHP_EOL;

        $checked[(string)$box] = 1;

        if($leftNextValue === WALL || $rightNextValue === WALL) {
            $foundObstruction = true;

            break;
        }

        $toBeMoved[] = $box;

        if ($leftNextValue != FLOOR || $rightNextValue != FLOOR) {
            if ($leftNextValue === BOX_LEFT) {
                $nextBox = new Box($leftNextCoord, $rightNextCoord);
                $queue[] = $nextBox;
            } elseif ($leftNextValue === BOX_RIGHT) {
                $nextBox = new Box($leftNextCoord->add(LEFT), $leftNextCoord);
                $queue[] = $nextBox;
            }

            if ($rightNextValue === BOX_LEFT) {
                $nextBox = new Box($rightNextCoord, $rightNextCoord->add(RIGHT));
                $queue[] = $nextBox;
            }
        }
    }

    if ($foundObstruction) {
        return false;
    }

    // Move everything
    $movers = array_reverse($toBeMoved);

    /** @var Box $boxMove */
    foreach ($movers as $boxMove) {
        $left = $boxMove->left;
        $right = $boxMove->right;

        $grid->add($left, FLOOR);
        $grid->add($right, FLOOR);

        $leftNew = $left->add($move);
        $rightNew = $right->add($move);

        $grid->add($leftNew, BOX_LEFT);
        $grid->add($rightNew, BOX_RIGHT);
    }

    return true;
}