<?php
class Coord
{
    public function __construct(
        public readonly int $x,
        public readonly int $y
    ){ }

    public function add(Coord $coord): Coord
    {
        return new Coord($this->x + $coord->x, $this->y + $coord->y);
    }

    public function __toString(): string
    {
        return $this->x . "-" . $this->y;
    }
}

const UP = new Coord(0, -1);
const LEFT = new Coord(-1, 0);
const UP_LEFT = new Coord(-1, -1);
const UP_RIGHT = new Coord(1, -1);
const RIGHT = new Coord(1, 0);
const DOWN = new Coord(0, 1);
const DOWN_LEFT = new Coord(-1, 1);
const DOWN_RIGHT = new Coord(1, 1);