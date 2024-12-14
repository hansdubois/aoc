<?php

$input = file_get_contents(__DIR__ . '/input.txt');

$machines = array_map(
    fn($machine) => explode(PHP_EOL, $machine),
    explode(PHP_EOL . PHP_EOL, $input)
);

/**
 *  buttonAX * buttonAPresses + buttonBX * buttonBPresses = PriceX
 *  buttonAY * buttonAPresses + buttonBY * buttonBPresses = PriceY
 *
 *  Solve for buttonAPress first by eliminating ButtonBPresses. Multiply with buttonBX and BY to be able to eliminate these.
 *
 *  buttonAX * buttonBY * buttonAPresses + buttonBX * buttonBPresses * buttonBY  = PriceX * buttonBY
 *  buttonAY * buttonBX * buttonAPresses + buttonBY * buttonBPresses * buttonBx = PriceY * buttonBx
 *
 *  Given that the Button B part is the same we can remove it.
 *  buttonAX * buttonBY * buttonAPresses + [[[buttonBX * buttonBPresses * buttonBY]]]  = PriceX * buttonBY
 *  buttonAY * buttonBX * buttonAPresses + [[[buttonBY * buttonBPresses * buttonBY]]] = PriceY * buttonBx
 *
 *  buttonAX * buttonBY * buttonAPresses = PriceX * buttonBY
 *  buttonAY * buttonBX * buttonAPresses = PriceY * buttonBx
 *
 *  Now we can subtract
 *  buttonAX * buttonBY * buttonAPresses = PriceX * buttonBY
 *  buttonAY * buttonBX * buttonAPresses = PriceY * buttonBx  ----
 *
 *  buttonAX *  buttonBY * buttonAPresses - buttonAY *  buttonBX * buttonAPresses =  PriceX * buttonBY - PriceY * buttonBx
 *
 *  We now successfully eliminated buttonBPresses, let's simplify
 *  (buttonAX * buttonBY - buttonAY * buttonBX) * buttonAPresses =  PriceX * buttonBY - PriceY * buttonBx
 *
 *  One more step
 *  buttonAPresses = PriceX * buttonBY - PriceY * buttonBx
 *                     ----------------------------------
 *                   buttonAX * buttonBY - buttonAY * buttonBX
 *
 *
 * Now we need to have the button B pressed.
 * We know how many times we pressed button A. So we know how far we have moved which is: buttonAX * buttonAPresses
 * Given we know how far we moved with button A we can calculate how many time we still need to move
 *
 * buttonAX * buttonAPresses + buttonBX * buttonBPresses = PriceX
 *
 * To isolate buttonBPresses:
 *
 * buttonBX * buttonBPresses = PriceX - buttonAX * buttonAPresses
 *
 * buttonBPresses = PriceX - buttonAX * buttonAPresses
 *                      ------------------------------
 *                              buttonBX
 */



$tokens = 0;
foreach ($machines as $machine) {
    [$instA, $instB, $instP] = $machine;

    preg_match_all("/(\d+)(\d+)/", $instA, $matchesA);
    [$buttonAXMovement, $buttonAYmovement] = $matchesA[0];

    preg_match_all("/(\d+)(\d+)/", $instB, $matchesB);
    [$buttonBXMovement, $buttonYMovement] = $matchesB[0];

    preg_match_all("/(\d+)(\d+)/", $instP, $matchesP);
    [$priceX, $priceY] = $matchesP[0];

    $pressedA = ($priceX * $buttonYMovement - $priceY * $buttonBXMovement) / ($buttonAXMovement * $buttonYMovement - $buttonAYmovement * $buttonBXMovement);
    $pressedB = ($priceX - $buttonAXMovement * $pressedA) / $buttonBXMovement;

    if (is_int($pressedA) && is_int($pressedB)) {
        $tokens += $pressedA * 3 + $pressedB;
    }
}

echo $tokens . PHP_EOL;