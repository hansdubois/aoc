<?php
$banks = preg_split('#\s+#', file_get_contents(__DIR__ . '/input.txt'));

$hashes = [];
$found = false;
$itt = 0;

while(!$found) {
    $itt++;

    $startHash = $hash = implode("-", $banks);

    // itteration
    $largestBank = 0;
    $numberToDistribute = 0;

    for ($i = 0; $i < count($banks); $i++) {
        if ($banks[$i] > $banks[$largestBank]) {
            $largestBank = $i;
        }
    }

    $numberToDistribute = $banks[$largestBank];

    // Reset to 0
    $banks[$largestBank] = 0;
    $position = $largestBank + 1;

    for ($i = $numberToDistribute; $i > 0; $i--) {
        if ($position == (count($banks) )) {
            $position = 0;
        }

        $banks[$position] = $banks[$position] + 1;

        $position++;
    }

    $hash = implode("-", $banks);

    if (in_array($hash, $hashes)) {
        $found = true;
    }

    $hashes[] = $hash;
}

echo "Part 1:" . $itt;
echo "Part 2:" . $itt - array_search($hash, $hashes) - 1;


