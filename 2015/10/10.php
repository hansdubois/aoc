<?php
ini_set("memory_limit", -1);

$input = '1';
$count = 6;

for ($i = 0; $i < $count; $i++)
{
    echo $input . PHP_EOL;
    $input = lookAndSay($input);
}

echo strlen($input);

function lookAndSay(string $in) :string
{
    $split = preg_split('/(.)(?!\1|$)\K/', $in);

    $out = '';

    foreach ($split as $charSet)
    {
        $out .= strlen($charSet);
        $out .= $charSet[0];
    }

    return $out;
}







