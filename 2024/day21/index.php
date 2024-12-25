<?php
$lines = explode(PHP_EOL, file_get_contents(__DIR__ . '/input.txt'));

$positions = [
    '7' => [ 0, 0 ],
    '8' => [ 0, 1 ],
    '9' => [ 0, 2 ],
    '4' => [ 1, 0 ],
    '5' => [ 1, 1 ],
    '6' => [ 1, 2 ],
    '1' => [ 2, 0 ],
    '2' => [ 2, 1 ],
    '3' => [ 2, 2 ],
    '0' => [ 3, 1 ],
    'A' => [ 3, 2 ],
    '^' => [ 0, 1 ],
    'a' => [ 0, 2 ],
    '<' => [ 1, 0 ],
    'v' => [ 1, 1 ],
    '>' => [ 1, 2 ]
];

$directions = [
    '^' => [ - 1, 0 ],
    'v' => [ 1, 0 ],
    '<' => [ 0, - 1 ],
    '>' => [ 0, 1 ],
];

$cache = [];

echo "Part 1: " . calculate($lines, 2) . PHP_EOL;
echo "Part s2 " . calculate($lines, 25) . PHP_EOL;

function calculate(array $lines, int $limit): int {
    $complexity = 0;

    foreach ($lines as $code) {
        $numeric = (int) substr($code, 0, 3);

        $complexity += $numeric * minLength($code, $limit);
    }

    return $complexity;
}

/**
 * Generates all valid moves between two positions while avoiding a specific position.
 */
function findMoves(array $start, array $end, array $avoid = [ 0, 0 ] ): array {
    global $directions;

    // Calculate the difference (delta) in row and column coordinates.
    $difference = [ $end[0] - $start[0], $end[1] - $start[1] ];
    $string     = '';

    // Vertical movement.
    if ( $difference[0] < 0 ) {
        $string .= str_repeat( '^', abs( $difference[0] ) );
    } else {
        $string .= str_repeat( 'v', $difference[0] );
    }

    // Horizontal movement.
    if ( $difference[1] < 0 ) {
        $string .= str_repeat( '<', abs( $difference[1] ) );
    } else {
        $string .= str_repeat( '>', $difference[1] );
    }

    // Generate all combinations of the combined move sequence.
    $combinations = combos( str_split( $string ) );
    $result       = [];

    foreach ( $combinations as $combination ) {
        $positions = [ $start ];

        // Simulate the sequence to track positions and ensure the "avoid" position is not crossed.
        foreach ( $combination as $dir ) {
            $last        = end( $positions );
            $positions[] = [ $last[0] + $directions[ $dir ][0], $last[1] + $directions[ $dir ][1] ];
        }

        // Add valid sequences that do not pass through the "avoid" position.
        if ( ! in_array( $avoid, $positions, true ) ) {
            $result[] = implode( '', $combination ) . 'a'; // Append 'a' to press the button.
        }
    }

    // Default to a single "a" press if no valid paths are found.
    return $result ?: [ 'a' ];
}

/**
 * Recursively calculates the minimum length of the moves to type a code on the keypad.
 */
function minLength(string $code, int $limit, int $depth = 0 ): int {
    global $cache, $positions;

    $key = $code . $depth . $limit;

    // Return cached result if already computed.
    if ( isset( $cache[ $key ] ) ) {
        return $cache[ $key ];
    }

    $avoid  = $depth === 0 ? [ 3, 0 ] : [ 0, 0 ];
    $cur    = $depth === 0 ? $positions['A'] : $positions['a'];
    $length = 0;

    foreach ( str_split( $code ) as $char ) {
        // Get the next position on the keypad.
        $next_cur = $positions[ $char ];

        // Get all valid move sequences between the current and next positions.
        $movesets = findMoves( $cur, $next_cur, $avoid );

        if ( $depth === $limit ) {
            // If at the last robot, use the shortest move directly.
            $length += strlen( $movesets[0] );
        } else {
            // Else, recursively compute the shortest sequence for all move options.
            $length += min( array_map( fn( $moveset ) => minLength( $moveset, $limit, $depth + 1 ), $movesets ) );
        }

        // Update the current position.
        $cur = $next_cur;
    }

    // Cache the result.
    $cache[ $key ] = $length;

    return $length;
}

/**
 * Generates all combinations of a given set of items.
 */
function combos(array $items): array {
    // A single item has only one combination.
    if ( count( $items ) === 1 ) {
        return [ $items ];
    }

    $combinations = [];

    foreach ( $items as $key => $item ) {
        // Remove the current item from the list.
        $remaining = $items;
        unset( $remaining[ $key ] );

        // Generate combinations for the remaining items.
        foreach (combos( $remaining ) as $combination ) {
            $combinations[] = array_merge( [ $item ], $combination );
        }
    }

    return $combinations;
}