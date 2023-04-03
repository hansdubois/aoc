<?php
ini_set('memory_limit', -1);

//$ingredients = [
//    new ingredient('Butterscotch', -1, -2, 6, 3, 8),
//    new ingredient('Cinnamon', 2, 3, -2, -1, 3)
//];

$ingredients = [
    new ingredient('Sprinkles', 2, 0, -2, 0, 3),
    new ingredient('Butterscotch', 0, 5, -3, 0, 3),
    new ingredient('Chocolate', 0, 0, 5, -1, 8),
    new ingredient('Candy', 0, -1, 0, 5, 8)
];

$totalTeaspoons = 100;
$minCount = 1;
$maxCount = $totalTeaspoons - count($ingredients);
$propertyIndexes =  [1, 2, 3, 4, 5];

$highestScore = 0;

foreach (getCombinations(count($ingredients), $totalTeaspoons) as $combination) {
    $total = 0;

    $ingredientIndexes = array_keys($combination);
    $totals = [];

    foreach ($propertyIndexes as $index)
    {
        $t = 0;

        foreach ($ingredientIndexes as $i)
        {
            $t += $ingredients[$i]->calculateScore($index, $combination[$i]);
        }

        if ($t < 0) {
            $totals[] = 0;
        } else {
            $totals[] = $t;
        }
    }

    if ($totals[4] === 500)
    {
        $score = array_product(array_slice($totals, 0, 4)) . PHP_EOL;

        if ($score > $highestScore) {
            $highestScore = $score;
        }
    }
}

var_dump($highestScore);

class ingredient {
    public function __construct(
        private string $name,
        private int $capacity,
        private int $durability,
        private int $flavor,
        private int $texture,
        private int $calories
    )
    {}

    public function getForCount(int $count): int {
        $total = 0;

        $total += ($this->capacity * $count) > 0 ? $this->capacity * $count : 0;
        $total += ($this->durability * $count) > 0 ? $this->durability * $count : 0;
        $total += ($this->flavor * $count) > 0 ? $this->flavor * $count : 0;
        $total += ($this->texture * $count) > 0 ? $this->texture * $count : 0;

        return $total;
    }

    public function calculateScore(int $index, int $amount): int {
        $list = [
            1 => $this->capacity,
            2 => $this->durability,
            3 => $this->flavor,
            4 => $this->texture,
            5 => $this->calories
        ];

        return $list[$index] * $amount;
    }
}

function getCombinations($count, $sum) {
    if ($count == 1) {
        yield array($sum);
    } else {
        foreach (range(0, $sum) as $i) {
            foreach (getCombinations($count - 1, $sum - $i) as $j) {
                yield array_merge(array($i), $j);
            }
        }
    }
}