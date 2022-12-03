<?php

$input = explode(PHP_EOL, file_get_contents(__DIR__ . '/input.txt'));

$niceCount = 0;

foreach ($input as $item) {
    if (checkForIllegalCombination($item) && checkForNiceCharacters($item) && checkForDoubleChars($item)) {
        $niceCount++;
    }
}

echo "Nice Strings: " . $niceCount;
echo PHP_EOL;
echo "All Strings: " . count($input);

function checkForNiceCharacters(string $input): bool
{
    $niceCharacters = 'aeiou';

    $niceCharactersChecked = array_filter(str_split($input), function (string $char) use ($niceCharacters) {
        return strstr($niceCharacters, $char) !== false;
    });

    return count($niceCharactersChecked) >= 3;
}

function checkForDoubleChars(string $input): bool
{
    for ($i = 0; $i < strlen($input); $i++) {
        if ($i > 0 && $input[$i - 1] === $input[$i]) {
            return true;
        }
    }

    return false;
}

function checkForIllegalCombination(string $input): bool
{
    $illegal = ['ab', 'cd', 'pq', 'xy'];

    foreach ($illegal as $combo) {
        if (is_string(strstr($input, $combo))) {
            return false;
        }
    }

    return true;
}