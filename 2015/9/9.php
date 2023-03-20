<?php
$input = file_get_contents('sample.txt');
$strings = explode("\n", $input);

$regex = "/(.*) to (.*) = (.*)/";

$cities = [];

foreach ($strings as $string) {
    $matches = [];
    preg_match($regex, $string, $matches);


    if (!array_key_exists($matches[1], $cities)) {
        $cities[$matches[1]] = [];
    }

    $cities[$matches[1]][$matches[2]] = (int)$matches[3];
    $cities[$matches[2]][$matches[1]] = (int)$matches[3];
}

$startPoints = array_keys($cities);
$allPaths = [];

foreach ($startPoints as $city)
{
   allPaths($city, '', [], $startPoints);
}

function allPaths($node, $path = '', $visited = [], $allCities, $paths = []) {
    $visited[] = $node;

    $notVisited = array_diff($allCities, $visited);

    if (empty($notVisited)) {
        global $cities, $allPaths;

        echo $path . '->' . $node . PHP_EOL;

        $way =  $path . '->' . $node;

        $nodes = explode("->", $way);

        $cost = 0;

        // skip last
        for ($i =0; $i < count($nodes) - 1; $i++)
        {
            $cost += $cities[$nodes[$i]][$nodes[$i + 1]];
        }

        $allPaths[$way] = $cost;

        return;
    }

    foreach ($notVisited as $notVisitedCity)
    {
        if ($path === '') {
            $nextPath = $node;
        } else {
            $nextPath = $path . "->" . $node;
        }

        allPaths($notVisitedCity, $nextPath, $visited, $allCities, $paths);
    }
}

var_dump(min($allPaths));
var_dump(max($allPaths));

