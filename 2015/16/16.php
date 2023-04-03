<?php
ini_set('memory_limit', -1);

$input = file_get_contents('input.txt');
$lines = explode("\n", $input);

$aunts = [];

foreach ($lines as $line) {
    $aunt = substr($line, 0, strpos($line, ':'));
    $auntCompounds = substr($line, strpos($line, ':') + 2);

    $compounds = [];

    foreach (explode(", ", $auntCompounds) as $auntCompound)
    {
        list($name, $value) = explode(": ", $auntCompound);

        $compounds[$name] = (int)$value;
    }

    $aunts[$aunt] = $compounds;
}

$signatures = [
    'children' => 3,
    'cats' => 7,
    'samoyeds' => 2,
    'pomeranians' => 3,
    'akitas' => 0,
    'vizslas' => 0,
    'goldfish' => 5,
    'trees' => 3,
    'cars' => 2,
    'perfumes' => 1
];

$leftOver = $aunts;

foreach ($signatures as $signature => $amount)
{
    $leftOver = array_filter($leftOver, function (array $auntCompounds) use ($signature, $amount) {
        if (!array_key_exists($signature, $auntCompounds)) {
            return true;
        }

        return $auntCompounds[$signature] === $amount;
    });
}

$leftOverRetroencabulator = $aunts;

foreach ($signatures as $signature => $amount)
{
    $leftOverRetroencabulator = array_filter($leftOverRetroencabulator, function (array $auntCompounds) use ($signature, $amount) {
        if (!array_key_exists($signature, $auntCompounds)) {
            return true;
        }

        if (in_array($signature, ['cats', 'trees']))
        {
            return $auntCompounds[$signature] > $amount;
        }

        if (in_array($signature, ['pomeranians', 'goldfish']))
        {
            return $auntCompounds[$signature] < $amount;
        }

        return $auntCompounds[$signature] === $amount;
    });
}

echo "Aunt found! : " . array_keys($leftOver)[0] . PHP_EOL;
echo "Retroencabulator Aunt found! : " . array_keys($leftOverRetroencabulator)[0];