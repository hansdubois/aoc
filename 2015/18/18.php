<?php
ini_set('memory_limit', -1);

$input = file_get_contents('input.txt');
$lines = explode("\n", $input);

$dimension = count($lines);
$field = array_fill(0, $dimension, array_fill(0, $dimension, "."));

for ($y = 0; $y < $dimension; $y++)
{
    $markers = str_split($lines[$y]);

    for ($x = 0; $x < $dimension; $x++)
    {
        $field[$y][$x] = $markers[$x];
    }
}

$field = new field($field, true);

$counts = 100;
for ($i = 0; $i < $counts; $i++)
{
    echo $i . PHP_EOL;
    $field->tick();
//    $field->draw();
    echo PHP_EOL;
}


echo "Amount of lights on: " . $field->getLightsOn();

class field {
    public function __construct(private array $field, private bool $cornersStayOn = false)
    {}

    public function draw(): void
    {
        foreach ($this->field as $y)
        {
            foreach ($y as $x)
            {
                echo $x;
            }

            echo PHP_EOL;
        }
    }

    public function tick()
    {
        $turnOn = [];
        $turnOff = [];

        foreach ($this->field as $y => $lines)
        {
            foreach ($lines as $x => $value)
            {
                if ($value === '.' && $this->shouldTurnOn($y, $x)) {
                    $turnOn[] = [$y, $x];
                }

                if ($value === '#' && !$this->shouldStayOn($y, $x))
                {
                    $turnOff[] = [$y, $x];
                }
            }
        }

        foreach ($turnOn as $assignment) {
            list($y, $x) = $assignment;

            $this->field[$y][$x] = '#';
        }

        foreach ($turnOff as $assignment) {
            list($y, $x) = $assignment;

            $this->field[$y][$x] = '.';
        }
    }

    private function doesPointExistOnField(int $y, int $x): bool {
        return array_key_exists($y, $this->field) && array_key_exists($x, $this->field[$y]);
    }

    private function isOn(int $y, int $x): bool {
        if ($this->isCorner($y, $x) && $this->cornersStayOn)
        {
            return true;
        }

        return $this->doesPointExistOnField($y, $x) && $this->field[$y][$x] === '#';
    }

    private function shouldTurnOn(int $y, int $x): bool {
        return $this->getNeighbourOnCount($y, $x) === 3;
    }

    private function shouldStayOn(int $y, int $x): bool {
        if ($this->isCorner($y, $x))
        {
            return true;
        }

        return in_array($this->getNeighbourOnCount($y, $x), [2, 3]);
    }

    private function isCorner(int $y, int $x)
    {
        $edge = count($this->field) - 1;

        return (
            ($y === 0 && $x === 0) ||
            ($y === 0 && $x === $edge) ||
            ($y === $edge && $x === 0) ||
            ($y === $edge && $x === $edge)
        );
    }

    public function getLightsOn(): int
    {
        $count = 0;
        foreach ($this->field as $y => $lines) {
            foreach ($lines as $x => $value) {
                if ($value === '#') {
                    $count++;
                }
            }
        }

        if ($this->cornersStayOn)
        {
            //$count += 4;
        }

        return $count;
    }

    private function getNeighbourOnCount(int $y, int $x)
    {
        $count = 0;

        if ($this->isOn($y - 1, $x -1)) {
            $count++;
        }

        if ($this->isOn($y - 1, $x)) {
            $count++;
        }

        if ($this->isOn($y - 1, $x + 1)) {
            $count++;
        }

        if ($this->isOn($y, $x +1)) {
            $count++;
        }

        if ($this->isOn($y + 1, $x +1)) {
            $count++;
        }

        if ($this->isOn($y + 1, $x)) {
            $count++;
        }

        if ($this->isOn($y + 1, $x - 1)) {
            $count++;
        }

        if ($this->isOn($y, $x - 1)) {
            $count++;
        }

        return $count;
    }
}