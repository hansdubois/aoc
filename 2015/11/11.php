#!/usr/bin/php
<?php
declare(strict_types=1);

function isValid($input) {
    if (preg_match('/[iol]/', $input)) { return false; }
    if (!preg_match('/(?:(.)\1).*(?:(.)\2)/', $input)) { return false; }

    for ($i = 0; $i < strlen($input); $i++) {
        if ($i >= 2) {
            if (ord($input[$i - 2]) + 1 == ord($input[$i - 1]) && ord($input[$i - 1]) + 1 == ord($input[$i])) {
                return true;
            }
        }
    }

    return false;
}

function nextPassword($input) {
    do {
        $input++;
    } while (!isValid($input));

    return $input;
}

$input = "cqjxxyzz";
echo nextPassword($input);


