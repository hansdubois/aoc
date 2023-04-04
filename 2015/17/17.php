<?php
ini_set('memory_limit', -1);

$arr = [20, 15, 10, 5, 5];
$input = [11, 30, 47, 31, 32, 36, 3, 1, 5, 3, 32, 36, 15, 11, 46, 26, 28, 1, 19, 3];

$combinations = powerSet($input);

$valid = array_filter($combinations, fn($combination) => array_sum($combination) === 150);

$countValues = array_values(array_map("count", $valid));
sort($countValues);

echo "Amount of combinations to fill 150 containers " . count($valid);
echo PHP_EOL;
echo "This can be done with the minimum of: " . $countValues[0];

function powerSet($array)
{
    $results = array(array());

    foreach ($array as $element) {
        foreach ($results as $combination) {
            $results[] = array_merge(array($element), $combination);
        }
    }
    return $results;
}