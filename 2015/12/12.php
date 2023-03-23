<?php
$input = file_get_contents('input.txt');
$lines = explode("\n", $input);

$numbers = [];

foreach ($lines as $line)
{
    preg_match('/(-?\d+)/', $line, $matches);

    if (count($matches) > 0)
    {
        $numbers[] = $matches[0];
    }
}

echo array_sum($numbers);