<?php
$targetRow = 2947;
$targetColumn = 3029;

$found = false;

$code = 20151125;

$currentRow = 1;
$currentColumn = 1;

while (!$found)
{
    if ($currentRow === $targetRow && $currentColumn === $targetColumn)
    {
        $found = true;

        echo PHP_EOL;
        echo "=========" . PHP_EOL;
        echo $code . PHP_EOL;
        echo "=========" . PHP_EOL;

        exit();
    }

    $code = ($code * 252533) % 33554393;

    if ($currentRow == 1) {
        $currentRow = $currentColumn + 1;
        $currentColumn = 1;
    } else {
        $currentRow--;
        $currentColumn ++;
    }

    echo sprintf("Location %d:%d, Code: %d", $currentColumn, $currentRow, $code) . PHP_EOL;
}