<?php
$input = file_get_contents(__DIR__ . '/input.txt');

$elves = explode("\n\n", $input);

$biggest = 0;
$listOfCalories = [];

foreach ($elves as $elf) {
    $calories = explode("\n", $elf);

    $total = array_sum($calories);

    $listOfCalories[] = $total;
}

rsort($listOfCalories);
$best = array_slice($listOfCalories, 0, 1);
$top3 = array_slice($listOfCalories, 0, 3);

echo "=============================";
echo "Total calories for top 3 elves: " . array_sum($top3);
echo PHP_EOL;
echo "Calories of Elf with most calories: " . $best[0];