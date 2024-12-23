<?php
$registerA = 21539243;
$registerB = 0;
$registerC = 0;
$program = array_map('intval', explode(',', "2,4,1,3,7,5,1,5,0,3,4,1,5,5,3,0"));

echo "Part 1: " . implode(",", compute($program, $registerA, $registerB, $registerC)) . PHP_EOL;
echo "Part 2: " . createRegister( 0, 1, $program) . PHP_EOL;

function compute(array $program, int $registerA, int $registerB, int $registerC): array {
    $pointer = 0;
    $output  = [];

    while ( $pointer < count( $program ) ) {
        $code = $program[ $pointer ];
        $op   = $program[ $pointer + 1 ];

        $combo = match ( $op ) {
            0, 1, 2, 3 => $op,
            4 => $registerA,
            5 => $registerB,
            6 => $registerC,
            default => - 1,
        };

        switch ( $code ) {
            case 0:
                $registerA = (int) ( $registerA / ( 2 ** $combo ) );
                $pointer += 2;
                break;
            case 1:
                $registerB ^= $op;
                $pointer += 2;
                break;
            case 2:
                $registerB  = $combo % 8;
                $pointer += 2;
                break;
            case 3:
                $pointer = ( $registerA !== 0 ) ? $op : $pointer + 2;
                break;
            case 4:
                $registerB ^= $registerC;
                $pointer += 2;
                break;
            case 5:
                $output[] = $combo % 8;
                $pointer  += 2;
                break;
            case 6:
                $registerB = (int) ( $registerA / ( 2 ** $combo ) );
                $pointer += 2;
                break;
            case 7:
                $registerC = (int) ( $registerA / ( 2 ** $combo ) );
                $pointer += 2;
                break;
        }
    }

    return $output;
}

function createRegister(int $input, int $pointer, array &$program ): bool|int {
    // We are done
    if ( $pointer > count($program ) ) {
        return $input;
    }

    // Loop over all 3-bit combinations (0–7) to explore possible values for the next 3 bits.
    for ( $i = 0; $i < 8; $i++ ) {
        // Append the current 3-bit value ($i) to the existing input using bitwise shift.
        $candidate = $input << 3 | $i;

        $output = compute( $program, $candidate, 0, 0 );

        // Check if the computed output matches the last $position elements of the program.
        if ( $output === array_slice( $program, - $pointer ) ) {
            // If the partial output matches, recursively try to extend the solution.
            $result = createRegister( $candidate, $pointer + 1, $program );

            // If a valid solution is found deeper in the recursion, return it.
            if ( $result !== false ) {
                return $result;
            }
        }
    }

    // If no valid combination works for this position, return false to backtrack.
    return false;
}