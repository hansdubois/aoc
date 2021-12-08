<?php
declare(strict_types=1);

$crabPositions = array_map('intval', explode(",", file_get_contents(__DIR__ . '/input.txt')));

sort($crabPositions);

$amountOfCrabs = count($crabPositions);

// Get the two middle ones and get the average. (https://thisinterestsme.com/php-calculate-median/)
$median = ceil(($crabPositions[$amountOfCrabs / 2 - 1] + $crabPositions[$amountOfCrabs / 2]) / 2);

$fuelCounter = 0;

foreach ($crabPositions as $position) {
    $fuelConsumption = $median - $position;

    if ($fuelConsumption < 0) {
        $fuelConsumption = $fuelConsumption * -1;
    }

    $fuelCounter += $fuelConsumption;
}

// Calculate fuel consumption for every step
$minPosition = min($crabPositions);
$maxPosition = max($crabPositions);

$travelCosts = array_fill(1, $maxPosition - $minPosition, 0);

for ($i = 0; $i < $maxPosition; $i++)
{
    $steps = array_fill(1, $i, 0);
    $travelCosts[$i] = array_sum(array_keys($steps));
}

$crabTravel = array_fill($minPosition, $maxPosition +1, 0);
for ($i = $minPosition; $i <= $maxPosition; $i++) {
    foreach ($crabPositions as $position) {
        $travel = $i - $position;
        if ($travel < 0 ) {
            $travel = $travel * -1;
        }

        if ($travel != 0) {
            $crabTravel[$i] += $travelCosts[$travel];
        }
    }
}

var_dump(min($crabTravel));
