<?php

$input = file_get_contents(__DIR__ . '/input.txt');

$map['santa'] = [
    [0, 0]
];

$map['robo'] = [
    [0, 0]
];

for($i = 0; $i < strlen($input); $i++)
{
    $direction = $input[$i];

    $key = $i % 2 == 0 ? 'santa' : 'robo';

    $lastPosition = end($map[$key]);

    switch($direction)
    {
        case '^':
            $map[$key][$i] = [$lastPosition[0] + 1, $lastPosition[1]];
            break;
        case 'v':
            $map[$key][$i] = [$lastPosition[0] - 1, $lastPosition[1]];
            break;
        case '>':
            $map[$key][$i] = [$lastPosition[0], $lastPosition[1] + 1];
            break;
        case '<':
            $map[$key][$i] = [$lastPosition[0], $lastPosition[1] - 1];
            break;
    }
}
echo 'Santa and RoboSanta were at ' . count(array_unique($map['santa'] + $map['robo'], SORT_REGULAR)) . ' houses' . PHP_EOL;