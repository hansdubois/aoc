<?php
$input = "5910927 0 1 47 261223 94788 545 7771";

$stones = array_map("intval", explode(" ", $input));
$stoneBlinkStorage = [];

$numberOfStones = 0;
$numberOfStoneTwo = 0;

require __DIR__ . '/../common/Stopwatch.php';

$stopwatch = new Stopwatch();
$stopwatch->start();

foreach($stones as $stone) {
    $numberOfStones += blink($stone, 25);
}

foreach($stones as $stone) {
    $numberOfStoneTwo += blink($stone, 75);
}

echo "Part 1: " . $numberOfStones . "\n";
echo "Part 2: " . $numberOfStoneTwo . "\n";

echo $stopwatch->ellapsed() . PHP_EOL;

function blink($stoneValue, $blinkCount) {
    $key = createStorageKey($stoneValue, $blinkCount);

    if (storageHas($key))
    {
        return getKey($key);
    }

    if ($blinkCount === 0) {
        $value = 1;
    } elseif ($stoneValue === 0) {
        // Stone is new
        $value = blink(1, $blinkCount - 1);
    } elseif (strlen($stoneValue) % 2 === 0) {
        // Stone needs to be splitted.
        [$left, $right] = str_split($stoneValue, strlen($stoneValue) / 2);
        $value = blink((int) $left, $blinkCount - 1) + blink((int) $right, $blinkCount - 1);
    } else {
        // times 2024
        $value = blink($stoneValue * 2024, $blinkCount - 1);
    }

    setStorage($key, $value);

    return $value;
}

function createStorageKey($stoneValue, $blinkCount): string {
    return $stoneValue . '--' . $blinkCount;
}

function storageHas($key) {
    global $stoneBlinkStorage;

    return isset($stoneBlinkStorage[$key]);
}

function getKey($key) {
    global $stoneBlinkStorage;

    return $stoneBlinkStorage[$key];
}

function setStorage($key, $value) {
    global $stoneBlinkStorage;

    $stoneBlinkStorage[$key] = $value;
}