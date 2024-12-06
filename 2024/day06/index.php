<?php
declare(strict_types = 0);

require __DIR__ . '/../common/Coord.php';
require __DIR__ . '/../common/Grid.php';


$lines = explode("\n", file_get_contents(__DIR__ . '/input.txt'));

const ICON_UP = '^';
const ICON_DOWN = 'v';
const ICON_LEFT = '<';
const ICON_RIGHT = '>';

class SecurityGrid extends Grid {
    public function printWithGuard(Coord $guardPosition, Coord $direction, array $visited, array $pha) : void {
        switch ($direction) {
            default:
            case UP:
                $icon = ICON_UP;
                break;
            case DOWN:
                $icon = ICON_DOWN;
                break;
            case LEFT:
                $icon = ICON_LEFT;
                break;
            case RIGHT:
                $icon = ICON_RIGHT;
                break;
        }

        $this->add($guardPosition, $icon);
        parent::printVisited($visited, "@");
        $this->add($guardPosition, ".");
        echo PHP_EOL;
    }

    public function getAllPointsInDirectionTill(Coord $guardPosition, Coord $moveDirection, string $till)
    {
        $cursorPosition = $guardPosition;

        $points = [];

        $canMove = true;
        while ($canMove) {
            $cursorPosition = $cursorPosition->add($moveDirection);

            if (!$this->existsOnGrid($cursorPosition)) {
                $canMove = false;
            } else {
                $value = $this->get($cursorPosition->x, $cursorPosition->y);

                if ($value === "#") {
                    $canMove = false;
                } else {
                    $points[] = $cursorPosition;
                }
            }
        }

        return $points;
    }
}

$grid = new SecurityGrid();
$guardPosition = null;
$guardDirection = UP;
$visited = $visitedWithDirection = [];

for($y = 0; $y < count($lines); $y++) {
    $pointsOnLine = str_split($lines[$y]);

    for ($x = 0; $x < count($pointsOnLine); $x++) {
        if ($pointsOnLine[$x] === ICON_UP) {
            $guardPosition = new Coord($x, $y);
            $grid->add($guardPosition, ".");
        } else {
            $grid->add(new Coord($x, $y), $pointsOnLine[$x]);
        }
    }
}

$outOfBounds = false;
$justRotated = false;
$loops = 0;
$phantoms = [];

while (!$outOfBounds) {
    $moveTo = $guardPosition->add($guardDirection);;

    if ($grid->existsOnGrid($moveTo)) {
        if ($grid->get($moveTo->x, $moveTo->y) == "#") {
            // Change direction
            switch ($guardDirection) {
                case UP:
                    $guardDirection = RIGHT;
                    break;
                case DOWN:
                    $guardDirection = LEFT;
                    break;
                case LEFT:
                    $guardDirection = UP;
                    break;
                case RIGHT:
                    $guardDirection = DOWN;
                    break;
            }

            $justRotated = true;
        } else {
            if (!$justRotated) {
                // What happens if next would be an obstacle?
                $moveDirection = $guardDirection;
                switch ($moveDirection) {
                    case UP:
                        $moveDirection = RIGHT;
                        break;
                    case DOWN:
                        $moveDirection = LEFT;
                        break;
                    case LEFT:
                        $moveDirection = UP;
                        break;
                    case RIGHT:
                        $moveDirection = DOWN;
                        break;
                }

                echo "Placing phantom rock: " . $moveTo->x . ", " . $moveTo->y . "\n";
                echo "Guard position: " . $guardPosition->x . ", " . $guardPosition->y . "\n";

                // Get all points till next obstacle
                $points = $grid->getAllPointsInDirectionTill($guardPosition, $moveDirection, "#");

                echo "Found " . count($points) . " points\n";

                // See if any of these points is visited in the same direction
                foreach ($points as $point) {
                    if (in_array($point . "-" . $moveDirection, $visitedWithDirection)) {
                        echo $point . "-" . $moveDirection;
                        $phantoms[] = $moveTo;
                        $loops++;
                        break;
                    }
                }
            }

            // Move
            $guardPosition = $moveTo;
            $visited[] = $moveTo;
            $visitedWithDirection[] = $moveTo . "-" . $guardDirection;

            $justRotated = false;
        }
    } else {
        $outOfBounds = true;
    }
}


$grid->printWithGuard($guardPosition, $guardDirection, $visited, $phantoms );

echo "Part 1: ". count(array_unique($visited)) . PHP_EOL;
echo "Part 2: ". $loops . PHP_EOL;