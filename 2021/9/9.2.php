<?php
declare(strict_types=1);


$lines = explode(PHP_EOL, file_get_contents(__DIR__ . '/input.txt'));

const GRID_WIDTH  = 10;

$map = array_fill(0, count($lines), []);

foreach ($lines as $i => $line) {
    $map[$i]  = array_map('intval', str_split($line));
}

$lowPoints = [];

for ($line = 0; $line < count($map); $line++)
{
    for ($position = 0; $position < count($map[$line]); $position++) {
        $valueToCheck = $map[$line][$position];

        $checkedPositions = 0;
        $lowPointCount = 0;

        // left
        if ($position > 0) {
            $checkedPositions++;

            if ($map[$line][$position - 1 ] > $valueToCheck) {
                $lowPointCount++;
            }
        }

        // top
        if ($line > 0) {
            $checkedPositions++;

            if ($map[$line -1][$position] > $valueToCheck) {
                $lowPointCount++;
            }
        }

        // bottom
        if ($line != (count($lines) -1)) {
            $checkedPositions++;

            if ($map[$line + 1][$position] > $valueToCheck) {
                $lowPointCount++;
            }
        }

        // right
        if ($position < (count($map[$line]) -1)) {
            $checkedPositions++;

            if ($map[$line][$position +1] > $valueToCheck) {
                $lowPointCount++;
            }
        }
        if ($lowPointCount === $checkedPositions) {
            $lowPoints[] = new Point($position, $line);
        }
    }
}

$basins = [];
$checked = [];

/** @var Point[] $lowPoints */
foreach ($lowPoints as $lowPoint)
{
    $itemsToCheck = [$lowPoint];
    $basinCount = 0;

    while (count($itemsToCheck) > 0) {
        $point = array_shift($itemsToCheck);
        $checked[$point->getY()][$point->getX()] = 'check';

        if ($map[$point->getY()][$point->getX()] === 9)
        {
            continue;
        }

        $basinCount++;

        echo $point->getX() , $point->getY() . PHP_EOL;

        $itemsToAdd = array_filter($point->createNeighbours(), function (Point $point) use ($map, $checked) {
            return $point->isInBounds() && !isset($checked[$point->getY()][$point->getX()]);
        });

        $itemsToCheck = $itemsToCheck + $itemsToAdd;

        //var_dump($itemsToCheck, $lowPoint, $itemsToAdd, $point->createNeighbours());
        //exit();
    }

    $basins[] = $basinCount;
}

rsort($basins);
var_dump($basins);

class Point {
    private int $x;
    private int $y;

    public function __construct(int $x, int $y) {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @return int
     */
    public function getX(): int
    {
        return $this->x;
    }

    /**
     * @return int
     */
    public function getY(): int
    {
        return $this->y;
    }

    public function isInBounds(): bool
    {
        return $this->x > 0 && $this->x < GRID_WIDTH && $this->y > 0 && $this-> y < GRID_WIDTH;
    }

    /**
     * @return self[]
     */
    public function createNeighbours(): array
    {
        $up = clone $this;
        $up->y = $up->y -1;

        $left = clone $this;
        $left->x = $left->x -1;

        $right = clone $this;
        $right->x = $right->x +1;

        $down = clone $this;
        $down->y = $down->y +1;

        return [$up, $left, $right, $down];
    }
}
echo array_sum($lowPoints);
