<?php

require __DIR__ . '/../common/Coord.php';
require __DIR__ . '/../common/Grid.php';

$lines = explode("\n", file_get_contents(__DIR__ . '/input.txt'));
$blocks = [];

foreach ($lines as $line) {
    [$x, $y] = explode(',', $line);

    $coord = new Coord($x, $y);

    $blocks[(string)$coord] = $coord;
}

$limit = 1024;

$boundedBlocks = array_slice($blocks, 0, $limit);

$width = 71;
$height = 71;

$grid = new Grid();

for ($y = 0; $y < $height; $y++) {
    for ($x = 0; $x < $width; $x++) {
        if (array_key_exists($x . "-" . $y, $boundedBlocks)) {
            $grid->add(new Coord($x, $y), "#");
        } else {
            $grid->add(new Coord($x, $y), ".");
        }
    }
}

$neighbourDirections = [UP, DOWN, LEFT, RIGHT];

$grid->print();

$start = new Coord(0,0);
$end = new Coord(70, 70);

echo "Part 1: " . findShortestPath($start, $end, $neighbourDirections, $grid) . PHP_EOL;

// Part 2
for ($i = $limit; $i < count($blocks); $i++) {
    /** @var Coord $block */
    $block = array_values(array_slice($blocks, $i, 1))[0];

    $grid->add($block, "#");

    if (findShortestPath($start, $end, $neighbourDirections, $grid) === 0) {
        echo "Part 2: " . (string)$block . PHP_EOL;

        break;
    }
}

function findShortestPath($start, $end, $neighbourDirections, $grid)
{
    $queue = [ [ $start, 0 ] ];
    $visited = [];

    while ($queue) {
        /** @var Coord $pos */
        [$pos, $steps] = array_shift($queue);

        if ( (string)$pos === (string)$end ) {
            return $steps;
        }

        foreach ($neighbourDirections as $direction) {
            $neighbour = $pos->add($direction);

            if ($grid->existsOnGrid($neighbour) && $grid->get($neighbour->x, $neighbour->y) === "." && !array_key_exists((string)$neighbour, $visited)) {

                $queue[] = [ $neighbour, $steps + 1 ];
                $visited[(string)$neighbour] = true;
            }
        }
    }

    return 0;
}
