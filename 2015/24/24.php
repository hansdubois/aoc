<?php
ini_set('memory_limit', -1);

$boxes = [
    1,
    2,
    3,
    7,
    11,
    13,
    17,
    19,
    23,
    31,
    37,
    41,
    43,
    47,
    53,
    59,
    61,
    67,
    71,
    73,
    79,
    83,
    89,
    97,
    101,
    103,
    107,
    109,
    113,
];


/**
 * Get all the Sets of the given array.
 *
 * @param $array Array to get sets from.
 * @param $maxLength Ignore sets larger than this size
 * @return Array of sets.
 */
function getAllSets($array, $maxlength = PHP_INT_MAX) {
    $result = array(array());

    foreach ($array as $element) {
        foreach ($result as $combination) {
            $set = array_merge(array($element), $combination);
            if (count($set) <= $maxlength) { $result[] = $set; }
        }
    }

    return $result;
}

function getMinimumBoxProduct($boxes, $bins) {
    $max = array_sum($boxes) / $bins;
    $smallest = PHP_INT_MAX;

    $found = false;
    for ($i = 0; $i < count($boxes); $i++) {
        foreach (getAllSets($boxes, $i) as $set) {
            if (array_sum($set) == $max) {
                $prod = array_product($set);
                if ($prod < $smallest) { $smallest = $prod; }
                $found = true;
            }
        }
        if ($found) { break; }
    }

    return $smallest;
}

echo 'Part 1: ', getMinimumBoxProduct($boxes, 3), "\n";
echo 'Part 2: ', getMinimumBoxProduct($boxes, 4), "\n";