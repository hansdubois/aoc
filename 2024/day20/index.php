<?php
declare(strict_types = 1);

require __DIR__ . '/../common/Coord.php';
require __DIR__ . '/../common/Grid.php';

$input = explode(PHP_EOL, file_get_contents(__DIR__ . '/input.txt'));

$directions = [UP, DOWN, LEFT, RIGHT];

$grid = new Grid();
$start = null;
$end = null;

for($y = 0; $y < count($input); $y++) {
    $charList = str_split($input[$y]);

    for ($x = 0; $x < count($charList); $x++) {
        $grid->add(new Coord($x, $y), $charList[$x]);

        if ($charList[$x] == 'S') {
            $start = new Coord($x, $y);
        } elseif ($charList[$x] == 'E') {
            $end = new Coord($x, $y);
        }
    }
}

$cheats = [];
$partTwo = [];

[$steps, $path] = findShortestPath($start, $end, $directions, $grid);

for($i = 0; $i < count($path); $i++) {
    [$x, $y] = explode("-", $path[$i]);
    $coord = new Coord((int)$x, (int)$y);

    foreach ($directions as $direction) {
        $check = $coord->add($direction);
        $ndCheck = $check->add($direction);

        if ($grid->existsOnGrid($check) &&
            $grid->existsOnGrid($ndCheck) &&
            $grid->get($check->x, $check->y) == "#" &&
            ($grid->get($ndCheck->x, $ndCheck->y) == "." || $grid->get($ndCheck->x, $ndCheck->y) == "E" )
        ) {
            $cheatPosition = array_search((string)$ndCheck, $path);

            $cheatSave = $cheatPosition - $i - 2;

            if ($cheatSave >= 100) {
                //echo "Cheat for: " .$cheatSave . PHP_EOL;

                $cheats[] = $cheatSave;
            }
        }
    }
}

for($i = 0; $i < count($path); $i++) {
    [$x, $y] = explode("-", $path[$i]);
    $coord = new Coord((int)$x, (int)$y);

    foreach ($directions as $direction) {
        $check = $coord->add($direction);

        if ($grid->existsOnGrid($check) &&
            $grid->get($check->x, $check->y) == "#" &&
            $grid->get($check->x, $check->y) != "S" &&
            $grid->get($check->x, $check->y) != "E"
        ) {
            for ($a = $i; $a < count($path); $a++) {
                [$xx, $yy] = explode("-", $path[$a]);

                $distance = abs((int)$xx - $x) + abs((int)$yy - $y);

                if ($distance > 1 && $distance <= 20) {
                    $cheatSave = $a - $i - $distance;

                    if ($cheatSave >= 100) {
                        echo "Cheat for: " .$cheatSave . PHP_EOL;

                        $partTwo[$path[$i].$xx.$yy] = $cheatSave;
                    }
                }
            }
        }
    }
}

//array_count_values($cheats);

echo "Part 1: " . count($cheats) . "\n";
echo "Part 2: " . count($partTwo) . "\n";


function findShortestPath($start, $end, $neighbourDirections, $grid)
{
    $queue = [[ $start, 0, [ (string)$start ]] ];
    $visited = [];

    while ($queue) {
        /** @var Coord $pos */
        [$pos, $steps, $path] = array_shift($queue);

        if ( (string)$pos === (string)$end ) {
            return [$steps, $path];
        }

        foreach ($neighbourDirections as $direction) {
            $neighbour = $pos->add($direction);

            if ($grid->existsOnGrid($neighbour) &&
                ($grid->get($neighbour->x, $neighbour->y) === "." || $grid->get($neighbour->x, $neighbour->y) === "E")
                && !array_key_exists((string)$neighbour, $visited)) {
                $queue[] = [ $neighbour, $steps + 1, array_merge($path, [(string)$neighbour])];

                $visited[(string)$neighbour] = true;
            }
        }
    }

    return [0, []];
}

$grid->print();