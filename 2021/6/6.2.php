<?php
declare(strict_types=1);
ini_set('memory_limit', '12G');

$state = array_map('intval', explode(",", file_get_contents(__DIR__ . '/input.txt')));
$school = array_fill(0, 9, 0);

foreach ($state as $fish) {
    $school[$fish]++;
}

$days = 256;
for ($i = 0; $i < $days; $i++) {
    $newSchool = array_fill(0, 9, 0);

    for ($timeToSpawn = 8; $timeToSpawn > 0; $timeToSpawn--){
        $newSchool[$timeToSpawn - 1] = $school[$timeToSpawn];
    }

    $newSchool[6] += $school[0];
    $newSchool[8] = $school[0];

    $school = $newSchool;
}

var_dump(array_sum($school));
exit();