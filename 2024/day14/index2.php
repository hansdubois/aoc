<?php

$input = explode("\n", file_get_contents(__DIR__ . '/input.txt'));
$movementTimes = 100;

$width = 101;
$height = 103;

$horizontalLine = intval(ceil($height / 2)) -1;
$verticalLine = intval(ceil($width / 2 )) -1;

$quadrants = [
    'A' => ['x' => ['from' => 0, 'to' => $verticalLine -1,], 'y' => ['from' => 0, 'to' => $horizontalLine -1]],
    'B' => ['x' => ['from' => $verticalLine + 1, 'to' => $width -1,], 'y' => ['from' => 0, 'to' =>$horizontalLine -1]],
    'C' => ['x' => ['from' => 0, 'to' => $verticalLine -1,], 'y' => ['from' => $horizontalLine + 1, 'to' => $height -1]],
    'D' => ['x' => ['from' => $verticalLine + 1, 'to' => $width -1,], 'y' => ['from' => $horizontalLine + 1, 'to' => $height -1]],
];

$quadrantCount = [
    'A' => 0,
    'B' => 0,
    'C' => 0,
    'D' => 0,
];

$positions = [];

require __DIR__ . '/../common/Coord.php';
require __DIR__ . '/../common/Grid.php';


class treeGrid extends Grid {
    public function __construct(
        readonly int $width,
        readonly int $height) {}

    public function return(): string
    {
        $return = '';

        for ($y = 0; $y < $this->height; $y++) {
            for ($x = 0; $x < $this->width; $x++) {
                if($this->existsOnGrid(new Coord($x, $y))) {
                    $return .= "@";
                } else {
                    $return .= ".";
                }
            }

            $return .= PHP_EOL;
        }

        return $return;
    }
}

$grid = new treeGrid($width, $height);

foreach ($input as $line) {
    [$position, $velocity] = explode(' ', $line);

    $position = str_replace("p=", "", $position);
    [$positionX, $positionY] = explode(',', $position);

    $velocity = str_replace("v=", "", $velocity);
    [$velocityX, $velocityY] = explode(',', $velocity);

    $grid->add(new Coord($positionX, $positionY), ['x' => $velocityX, 'y' => $velocityY]);
}

for($step = 0; $step < 8051; $step++) {
    $points = $grid->getItemsOnGrid();

    $grid = new treeGrid($width, $height);

    foreach ($points as $y => $xs) {
        foreach ($xs as $x => $velocity) {
            $movement = ['x' => $velocityX * $movementTimes, 'y' => $velocityY * $movementTimes];

            $movementX = $movement['x'] % $width;
            $movementY = $movement['y'] % $height;

            $positionX = $x + $velocity['x'];
            $positionY = $y + $velocity['y'];

            if ($positionX >= $width) {
                $positionX -= $width;
            } elseif ($positionX < 0) {
                $positionX += $width;
            }

            if ($positionY >= $height) {
                $positionY -= $height;
            } elseif ($positionY < 0) {
                $positionY += $height;
            }

            $grid->add(new Coord($positionX, $positionY), $velocity);
        }
    }

    $return = "========================================". PHP_EOL;
    $return .= "=========== {$step} ====================" . PHP_EOL;
    $return .= "========================================" . PHP_EOL;
    $return .= $grid->return();

    echo $grid->return();

    //file_put_contents(__DIR__ . '/output.txt', $return, FILE_APPEND);
}

echo $grid->return();

echo "Part one: ". array_product($quadrantCount) . "\n";