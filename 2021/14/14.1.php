<?php
declare(strict_types=1);

$startString = 'NNCB';

$lines = explode(PHP_EOL, file_get_contents(__DIR__ . '/input.txt'));
$replaces = [];

foreach ($lines as $line)
{
    [$find, $replace] = explode(' -> ', $line);

    $replace = $find[0] . $replace . $find[1];

    $replaces[$find] = $replace;
}

var_dump($replaces);

$steps = 2;
for ($i = 0; $i < $steps; $i++)
{
    $found = array_filter(array_keys($replaces), function ($search) use ($startString)  {
        return strstr($startString, $search);
    });

    foreach ($found as $search)
    {
        $startString = str_replace($search, $replaces[$search], $startString);
    }

   echo $startString . PHP_EOL;
}

$cleanChars = array_filter(count_chars($startString), fn ($char) => intval($char) > 0);

var_dump($cleanChars);
var_dump(max($cleanChars) - min($cleanChars));


