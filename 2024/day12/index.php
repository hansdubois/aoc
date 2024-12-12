<?php
declare(strict_types = 1);

require __DIR__ . '/../common/Coord.php';
require __DIR__ . '/../common/Grid.php';

require __DIR__ . '/../common/Stopwatch.php';

$input = explode("\n", file_get_contents(__DIR__ . '/input.txt'));
$grid = new Grid();

for($y = 0; $y < count($input); $y++) {
    $chars = str_split($input[$y]);

    for ($x = 0; $x < count($chars); $x++) {
        $coord = new Coord($x, $y);

        $grid->add($coord, $chars[$x]);
    }
}

/** @var Coord $directions */
$directions = [
    UP,
    DOWN,
    LEFT,
    RIGHT
];

$corners = [
    [UP, LEFT],
    [UP, RIGHT],
    [DOWN, LEFT],
    [DOWN, RIGHT],
];

$visited = [];

$part1 = 0;
$part2 = 0;

for($y = 0; $y < count($input); $y++) {
    for ($x = 0; $x < count(str_split($input[$y])); $x++) {
        $fence = 0;
        $fencePosition = [];
        $amountOfPlants = 0;
        $cornerCount = 0;
        $cornerList = [];

        $sides = [];

        $queue = [new Coord($x, $y)];

        while($queue) {
            $neighbours = [];
            $position = array_shift($queue);

            if(array_key_exists((string)$position, $visited)) {
                continue;
            }

            // Key based is faster
            $visited[(string)$position] = 1;

            $amountOfPlants++;

            foreach ($directions as $direction) {
                /** @var Coord $direction */
                $checking = $position->add($direction);
                $current = $grid->get($x, $y);

                // Part 1
                if ($grid->existsOnGrid($checking)) {
                    // Fence
                    if ($grid->get($checking->x, $checking->y) != $current) {
                        $fence++;
                        $fencePosition[(string)$direction][(string)$position] = 1;
                    }
                    // Same Plant
                    else {
                        $queue[] = $checking;
                        $neighbours[] = $direction;
                    }
                } else {
                    $fence++;

                    $fencePosition[(string)$direction][(string)$position] = 1;
                }
            }
        }

        foreach ($fencePosition as $fenceDirection)
        {
            $cornersVisited = [];
            foreach ($fenceDirection as $fencePos => $number)
            {
                if (!isset($cornersVisited[$fencePos])) {
                    $cornerCount++;

                    $cornersQueue = [explode("-", (string)$fencePos)];

                    while ($cornersQueue)
                    {
                        [$cornerY, $cornerX] = array_shift($cornersQueue);
                        $key = "$cornerY-$cornerX";

                        if (isset($cornersVisited[$key])) {
                            continue;
                        }

                        $cornersVisited[$key] = 1;

                        foreach ($directions as $direction)
                        {
                            [$newY, $newX] = [$cornerY + $direction->y, $cornerX + $direction->x];

                            if (isset($fenceDirection["$newY-$newX"])) {
                                $cornersQueue[] = [$newY, $newX];
                            }
                        }
                    }
                }
            }
        }

        $part1 += $amountOfPlants * $fence;
        $part2 += $amountOfPlants * $cornerCount;
    }
}

echo "Part 1: ". $part1 . PHP_EOL;
echo "Part 2: ". $part2 . PHP_EOL;