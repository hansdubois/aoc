<?php

function sumJson($json, $exclude = array()) {
    $total = 0;

    foreach ($json as $part) {
        if (is_array($part)) {
            $total += sumJson($part, $exclude);
        } else if (is_object($part)) {
            $hasBad = false;
            foreach ($part as $val) {
                if (is_string($val) && in_array((string)$val, $exclude)) {
                    $hasBad = true;
                    break;
                }
            }
            if (!$hasBad) { $total += sumJson($part, $exclude); }
        } else if (is_numeric($part)) {
            $total += $part;
        }
    }

    return $total;
}

// Now actually do something more clever for part 2.
$json = json_decode(file_get_contents("input.txt"));
echo 'Part 2 Total Count: ', sumJson($json, array("red")), "\n";