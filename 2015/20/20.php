<?php

ini_set('memory_limit', -1);

$houses = 10000000;
$presentsToLookFor = 34000000;

for ($houseNumber = 786200; $houseNumber <= $houses; $houseNumber++)
{
    $presentsAtHouse = array_sum(elvesThatWillVisitHouse($houseNumber)) * 10;

    if ($presentsAtHouse >= $presentsToLookFor) {
        echo sprintf("House %d got %d presents.", $houseNumber, $presentsAtHouse) . PHP_EOL;
        exit();
    }

    echo $houseNumber . PHP_EOL;
}

for ($houseNumber = 831400; $houseNumber <= $houses; $houseNumber++)
{
    $presentsAtHouse = array_sum(elvesThatWillVisitHouseWithMax($houseNumber)) * 11;

    if ($presentsAtHouse >= $presentsToLookFor) {
        echo sprintf("House %d got %d presents.", $houseNumber, $presentsAtHouse) . PHP_EOL;
        exit();
    }

    echo $houseNumber . "--". $presentsAtHouse . PHP_EOL;
}

function elvesThatWillVisitHouse(int $number) {
    $divisors = [];

    for ($i = $number; $i > 0; $i--)
    {
        if ($number % $i === 0)
        {
            $divisors[] = $i;
        }
    }

    return $divisors;
}

function elvesThatWillVisitHouseWithMax(int $number) {
    $divisors = [];

    for ($i = $number; $i > 0; $i--)
    {
        if ($number % $i === 0 && $number / $i >= 50)
        {
            $divisors[] = $i;
        }
    }

    return $divisors;
}


