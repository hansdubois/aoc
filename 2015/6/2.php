<?php
ini_set('memory_limit', '12G');

$grid = array_fill(0, 999, array_fill(0, 999, 0));

$actions = array_map(
    function (string $line):action { return action::fromLine($line);},
    explode(PHP_EOL, file_get_contents(__DIR__ . '/input.txt'))
);

$counter = 0;

foreach ($actions as $action) {
    $coordinates = createCoords($action->getFrom(), $action->getTo());

    switch ($action->getAction()) {
        case $action::ACTION_ON :
            foreach ($coordinates as $coordinate) {
                $value = $grid[$coordinate->getX()][$coordinate->getY()];
                $grid[$coordinate->getX()][$coordinate->getY()] = $value + 1;
            }
            break;

        case $action::ACTION_OFF :
            foreach ($coordinates as $coordinate) {
                if ( $grid[$coordinate->getX()][$coordinate->getY()] > 0) {
                   $grid[$coordinate->getX()][$coordinate->getY()] =  $grid[$coordinate->getX()][$coordinate->getY()] - 1;
                }
            }
            break;

        case $action::ACTION_TOGGLE :
            foreach ($coordinates as $coordinate) {
                $grid[$coordinate->getX()][$coordinate->getY()] = $grid[$coordinate->getX()][$coordinate->getY()] + 2;;
            }

            break;
    }
}

$a = 0;
foreach ($grid as $row) {
    foreach ($row as $column) {
        $a = $a + $column;
    }
}
echo "asdf"; echo PHP_EOL;
echo "asdf"; echo PHP_EOL;
echo "asdf"; echo PHP_EOL;
echo "asdf"; echo PHP_EOL;
echo "asdf"; echo PHP_EOL;
var_dump($a);

class action {
    const ACTION_ON = 'on';
    const ACTION_OFF = 'off';
    const ACTION_TOGGLE = 'toggle';

    public function __construct(
        private string $action,
        private coord $from,
        private coord $to
    ){}

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return coord
     */
    public function getFrom(): coord
    {
        return $this->from;
    }

    /**
     * @return coord
     */
    public function getTo(): coord
    {
        return $this->to;
    }

    public static function fromLine(string $line) {
        $action = '';

        if (str_starts_with($line, "turn on")){
            $action = self::ACTION_ON;
        } elseif (str_starts_with($line, "turn off")) {
            $action = self::ACTION_OFF;
        } elseif (str_starts_with($line, "toggle")) {
            $action = self::ACTION_TOGGLE;
        }

        $matches = [];
        preg_match_all('/(\d){1,3}\,(\d){1,3}/', $line, $matches);

        list($x, $y) = explode(',', $matches[0][0]);
        $from = new coord($x, $y);

        list($x, $y) = explode(',', $matches[0][1]);
        $to = new coord($x, $y);

        return new self($action, $from, $to);
    }
}

/**
 * @param coord $from
 * @param coord $to
 * @return coord[]
 */
function createCoords(coord $from, coord $to) : array
{
    $coords = [];

    for ($row = $from->getX(); $row <= $to->getX(); $row++) {
        for ($column = $from->getY(); $column <= $to->getY(); $column++) {
            $coords[] = new coord($row, $column);
        }
    }

    return $coords;
}

class coord {
    public function __construct(
        private int $x,
        private int $y
    ){}

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
}