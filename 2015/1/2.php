<?php

$input = file_get_contents(__DIR__ . '/input.txt');

$floor = 0;

for ($i = 0; $i < strlen($input); $i++ ){
    ($input[$i] === '(') ? $floor++ : $floor--;

    if ($floor < 0) {
        echo $i +1;
        die($i + 1);
    }
}