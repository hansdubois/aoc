<?php

declare(strict_types=1);

$input = explode(PHP_EOL, file_get_contents(__DIR__ . '/input.txt'));

$movements = array_map(function (string $item): movement {
    [$direction, $units] = explode(" ", $item);

    return new movement((string)$direction, (int)$units);
}, $input);

$position = new position(0,0);

foreach ($movements as $movement)
{
    $position->move($movement);
}

echo $position->getDepth() * $position->getHorizontalPosition();

class position {
    private int $horizontalPosition;
    private int $depth;
    private int $aim = 0;

    public function __construct(int $startHorizontalPosition, int $startDepth)
    {
        $this->horizontalPosition = $startHorizontalPosition;
        $this->depth = $startDepth;
    }

    public function move(movement $movement)
    {
        switch($movement->getDirection()) {
            case movement::DIRECTION_UP :
                $this->aim -= $movement->getUnits();;
                break;
            case $movement::DIRECTION_DOWN:
                $this->aim += $movement->getUnits();
                break;
            case $movement::DIRECTION_FORWARD:
                $this->horizontalPosition += $movement->getUnits();
                $this->depth += ($this->aim * $movement->getUnits());
                break;
        }
    }

    public function getHorizontalPosition(): int
    {
        return $this->horizontalPosition;
    }

    public function getDepth(): int
    {
        return $this->depth;
    }
}

class movement {
    public const DIRECTION_FORWARD = 'forward';
    public const DIRECTION_UP = 'up';
    public const DIRECTION_DOWN = 'down';

    private string $direction;
    private int $units;

    public function __construct(string $direction, int $units)
    {
        $allowedDirections = [
            self::DIRECTION_DOWN,
            self::DIRECTION_FORWARD,
            self::DIRECTION_UP
        ];

        if (!in_array($direction, $allowedDirections)) {
            throw new InvalidArgumentException('Direction invalid: ' . $direction);
        }

        $this->direction = $direction;
        $this->units = $units;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }

    public function getUnits(): int
    {
        return $this->units;
    }
}