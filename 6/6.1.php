<?php
declare(strict_types=1);
ini_set('memory_limit', '12G');

$state = array_map('intval', explode(",", file_get_contents(__DIR__ . '/input.txt')));
$days = 256;

for ($i = 0; $i < $days; $i++) {
    $fishToAdd = 0;

    $state = array_map(function (int $fish) {
        if ($fish == 0) {
            global $fishToAdd;

            $fishToAdd = $fishToAdd + 1;

            return 6;
        }

        return $fish - 1;
    }, $state);

    $state = $state + array_fill(count($state), $fishToAdd, 8);
}

var_dump(count($state));