<?php
declare(strict_types = 1);

require __DIR__ . '/../common/Coord.php';
require __DIR__ . '/../common/Grid.php';

$lines = explode("\n", file_get_contents(__DIR__ . '/input.txt'));

$grid = new Grid();
$start = null;
$end = null;

for($y = 0; $y < count($lines); $y++) {
    $chars = str_split($lines[$y]);

    for ($x = 0; $x < count($chars); $x++) {
        $grid->add(new Coord($x, $y), $chars[$x]);

        if ($chars[$x] == 'S') {
            $start = new Coord($x, $y);
        } elseif ($chars[$x] == 'E') {
            $end = new Coord($x, $y);
        }
    }
}

foreach(breadthFirstSearch($start, $end, $grid) as $result) {
    $grid->printWithPath($result[0]) ;
    echo $result[1] + 1;
    echo PHP_EOL;
}



function breadthFirstSearch(Coord $start, Coord $end, Grid $grid)
{
    $directions = [UP, DOWN, LEFT, RIGHT];
    $visited = [];
    $queue = [[$start, [], null, 0]];

    $path = [];

    while ($queue) {
        [$current, $history, $dir, $score] = array_shift($queue);

        if ($current === $end) {
            $path[] = $history;
            break;
        }

        if (!array_key_exists((string)$current.(string)$dir, $visited)) {
            $visited[(string)$current.(string)$dir] = 1;

            foreach ($directions as $direction) {
                $checking = $current->add($direction);

                if ((string)$checking === (string)$end) {
                    $path[] = [$history, $score];
                }

                if ($grid->existsOnGrid($checking) && $grid->get($checking->x, $checking->y) != "#") {
                    $newScore = $score;
                    $newScore += 1;
                    if ((string)$dir !== (string)$direction) {
                        $newScore += 1000;
                    }

                    $queue[] = [$checking, array_merge($history, [$current]), $direction, $newScore];
                }
            }
        }
    }

    return $path;
}