<?php
declare(strict_types = 0);

require __DIR__ . '/../common/Coord.php';
require __DIR__ . '/../common/Grid.php';

$input = explode("\n", file_get_contents(__DIR__ . '/input.txt'));
$grid = new Grid();
$antennas = [];

for($y = 0; $y < count($input); $y++) {
    $chars = str_split($input[$y]);

    for ($x = 0; $x < count($chars); $x++) {
        $coord = new Coord($x, $y);
        $grid->add($coord, $chars[$x]);

        if ($chars[$x] != ".") {
            $antennas[$chars[$x]][] = $coord;
        }
    }
}

$frequencies = array_keys($antennas);

$antiNodes = [];
$antiNodesPartTwo = [];

// Part One
foreach ($frequencies as $frequency) {
    /**
     * @var  Coord[] $antennasWithFrequency
     */
    $antennasWithFrequency = $antennas[$frequency];

    // Part one
    for ($source = 0; $source < count($antennasWithFrequency); $source++) {
        for ($target = $source + 1; $target < count($antennasWithFrequency); $target++) {
            $sourceAntenna = $antennasWithFrequency[$source];
            $targetAntenna = $antennasWithFrequency[$target];

            $direction = $targetAntenna->direction($sourceAntenna);

            $antiNodeOne = $sourceAntenna->subtract($direction);
            $antiNodeTwo = $targetAntenna->add($direction);

            if ($grid->existsOnGrid($antiNodeOne)) {
                $antiNodes[] = $antiNodeOne;
            }

            if ($grid->existsOnGrid($antiNodeTwo)) {
                $antiNodes[] = $antiNodeTwo;
            }
        }
    }

    // Part 2
    foreach ( $antennasWithFrequency as $antenna ) {
        // all antennas are antinodes.
        $antiNodesPartTwo[] = $antenna;
    }

    // Part one
    for ($source = 0; $source < count($antennasWithFrequency); $source++) {
        for ($target = $source + 1; $target < count($antennasWithFrequency); $target++) {
            $sourceAntenna = $antennasWithFrequency[$source];
            $targetAntenna = $antennasWithFrequency[$target];

            $direction = $targetAntenna->direction($sourceAntenna);

            // Find all forward
            $point = $sourceAntenna;
            while ( true ) {
                $point = $point->add($direction);

                if (!$grid->existsOnGrid($point)) {
                    break;
                }

                $antiNodesPartTwo[] = $point;
            }

            // Find all backward
            $point = $sourceAntenna;
            while ( true ) {
                $point = $point->subtract($direction);
                
                if (!$grid->existsOnGrid($point)) {
                    break;
                }

                $antiNodesPartTwo[] = $point;
            }
        }
    }
}


echo "Part 1:" . count(array_unique($antiNodes)) . PHP_EOL;
echo "Part 2:" . count(array_unique($antiNodesPartTwo)) . PHP_EOL;
