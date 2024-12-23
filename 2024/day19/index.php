<?php
$input = trim(file_get_contents(__DIR__ . '/input.txt'));

[$availablePatterns, $designs] = explode("\n\n", $input);

$availablePatterns = explode(", ", $availablePatterns);
$designs = explode("\n", $designs);

$cache = [];

function isValidDesign(string $design, array $available): int
{
    global $cache;

    if($design == "") {
        return true;
    }

    if (array_key_exists($design, $cache)) {
        return $cache[$design];
    }

    $validCount = 0;

    foreach ($available as $a) {
        if (str_starts_with($design, $a)) {
            $validCount += isValidDesign(substr($design, strlen($a)), $available);
        };
    }

    $cache[$design] = $validCount;

    return $validCount;
}

$pt1 = 0;
$pt2 = 0;

foreach ($designs as $d) {
    $validCount = isValidDesign($d, $availablePatterns);
    if ($validCount > 0) $pt1++;

    $pt2 += $validCount;
}

echo "Part 1:" . $pt1 . "\n";
echo "Part 2:" . $pt2 . "\n";