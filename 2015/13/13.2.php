<?php
ini_set('memory_limit', -1);

$input = file_get_contents('input.txt');
$lines = explode("\n", $input);

$participants = [];
$participants[] = 'Hans';

$participantPoints = [];

foreach ($lines as $line)
{
    $firstName = substr($line, 0, strpos($line, " "));


    if (!array_key_exists($firstName, $participantPoints)) {
        $participants[] = $firstName;
        $participantPoints[$firstName] = [];
        $participantPoints[$firstName]['Hans'] = 0;
        $participantPoints['Hans'][$firstName] = 0;
    }

    $secondName = str_replace([" ", "."], "", substr($line, strrpos($line, " ")));

    preg_match('/(gain|lose) (\d+)/', $line, $matches);

    $participantPoints[$firstName][$secondName] = ($matches[1] === 'gain') ? (int)$matches[2] : (int) ($matches[2] * -1);
}

$allSeatings = getPermutations($participants);
$happiest = 0;

foreach($allSeatings as $seating)
{
    $count = 0;

    foreach ($seating as $index => $person) {
        if ($index == 0) {
            $count += $participantPoints[$person][$seating[count($seating) - 1]];
            $count += $participantPoints[$person][$seating[$index +1]];
        } elseif ($index == count($seating) -1) {
            $count += $participantPoints[$person][$seating[0]];
            $count += $participantPoints[$person][$seating[$index -1]];
        } else {
            $count += $participantPoints[$person][$seating[$index +1]];
            $count += $participantPoints[$person][$seating[$index -1]];
        }
    }

    if ($count > $happiest) {
        $happiest = $count;
    }
}

echo $happiest;

function getPermutations($items, $perms = array()) {
    if (empty($items)) {
        $return = array($perms);
    } else {
        $return = array();
        for ($i = count($items) - 1; $i >= 0; --$i) {
            $newitems = $items;
            $newperms = $perms;
            list($foo) = array_splice($newitems, $i, 1);
            array_unshift($newperms, $foo);
            $return = array_merge($return, getPermutations($newitems, $newperms));
        }
    }
    return $return;
}


