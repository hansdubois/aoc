<?php

class Grid {
    protected array $data = [];

    public function init(int $width, int $height, mixed $default = null) {
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $this->data[$y][$x] = $default;
            }
        }
    }

    public function add(Coord $coord, mixed $value) : void {
        $this->data[$coord->y][$coord->x] = $value;
    }

    public function get(int $x, int $y) : mixed
    {
        return $this->data[$y][$x];
    }

    public function print() : void {
        foreach ($this->data as $y => $row) {
            foreach ($row as $x => $value) {
                echo (string)$this->data[$y][$x];
            }

            echo "\n";
        }
    }

    public function printVisited(array $visited, string $visitedValue) : void {
        foreach ($this->data as $y => $row) {
            foreach ($row as $x => $value) {
                if (in_array(new Coord($x, $y), $visited)) {
                    echo $visitedValue;
                } else {
                    echo (string)$this->data[$y][$x];
                }
            }

            echo "\n";
        }
    }
    public function printWithPath(array $path) : void {
        $colorNumber = 16;

        foreach ($path as $part) {
            $this->add($part, "*");
        }


        foreach ($this->data as $y => $row) {
            foreach ($row as $x => $value) {
                if ($value == "*") {
                    print "\e[48;5;". $colorNumber. "m";
                    echo "*";
                    print "\e[0m";

                    if($colorNumber >=256) {
                        $colorNumber = 16;
                    }

                } else {
                    echo $value;
                }
                //echo (string)$this->data[$y][$x];
            }

            $colorNumber++;

            echo "\n";
        }
    }

    public function returnAllSurroundingValues(Coord $coord): array {
        $coords = [
            $coord->add(LEFT),
            $coord->add(RIGHT),
            $coord->add(UP),
            $coord->add(DOWN),
            $coord->add(UP_LEFT),
            $coord->add(UP_RIGHT),
            $coord->add(DOWN_LEFT),
            $coord->add(DOWN_RIGHT),
        ];

        return array_filter(array_map(function (coord $coord) {
            if ($this->existsOnGrid($coord)) {
                return $this->data[$coord->y][$coord->x];
            }
        }, $coords), function($value) { return !!$value; });
    }

    public function existsOnGrid(Coord $coord): bool
    {
        return (array_key_exists($coord->y, $this->data) &&
            array_key_exists($coord->x, $this->data[$coord->y])
        );
    }

    public function getItemsOnGrid(): array {
        return $this->data;
    }

    public function getAllItemsThatHaveValue($value): array {
        $return = [];

        foreach ($this->data as $y => $row) {
            foreach ($row as $x => $gridValue) {
                if ($gridValue == $value) {
                    $return[] = new Coord($x, $y);
                }
            }
        }

        return $return;
    }
}