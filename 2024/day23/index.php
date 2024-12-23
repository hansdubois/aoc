<?php
$networkPairs = array_map(function ($item) {
    return explode("-", $item);
}, explode(PHP_EOL, trim(file_get_contents(__DIR__ . '/input.txt'))));

$connections = [];
$connectionsStartWithT = [];

foreach ($networkPairs as $pair) {
    if (!array_key_exists($pair[0], $connections)) {
        $connections[$pair[0]] = [];
    }

    if (!array_key_exists($pair[1], $connections)) {
        $connections[$pair[1]] = [];
    }

    $connections[$pair[0]][] = $pair[1];
    $connections[$pair[1]][] = $pair[0];

    if (str_starts_with($pair[0], "t")) {
        $connectionsStartWithT[] = $pair[0];
    }

    if (str_starts_with($pair[1], "t")) {
        $connectionsStartWithT[] = $pair[1];
    }
}

$connectionsStartWithT = array_unique($connectionsStartWithT);

$combos = [];

foreach ($connectionsStartWithT as $start) {
    foreach ($connections[$start] as $connection) {
        foreach ($connections[$connection] as $third) {
            if (in_array($connection, $connections[$third]) && in_array($start, $connections[$third])) {
                $nodes = [$start, $connection, $third];
                sort($nodes);

                $combos[] = implode("-", $nodes);
            }
        }
    }
}

var_dump(count(array_unique($combos)));