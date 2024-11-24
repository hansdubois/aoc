<?php
$validCount = 0;
$parTwoCount = 0;

$input = file_get_contents(__DIR__ . '/real_input.txt');
$rows = explode("\n", $input);

foreach ($rows as $row) {
    $allWords = explode(" ", $row);

    if (count($allWords) === count(array_unique($allWords))) {
        $validCount++;
    }

    $partTwoWords = array_map(function ($word) {
        $a = str_split($word);
        sort($a);

        return implode("", $a);
    }, $allWords);

    if (count($partTwoWords) === count(array_unique($partTwoWords))) {
        $parTwoCount++;
    }
}

echo "Part 1: " . $validCount . "\n";
echo "Part 2: " . $parTwoCount . "\n";