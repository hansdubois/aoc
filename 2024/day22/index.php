<?php
declare(strict_types = 1);
ini_set('memory_limit', '-1');

$initialSecrets = array_map('intval', explode("\n", file_get_contents(__DIR__ . '/input.txt')));

function evolveSecret(int $secret): int{
    // Run for 2000 times.
    for ($i = 0; $i < 2000; $i++) {
        // Multiply by 64, mix, prune.
        $secret = (($secret * 64) ^ $secret) % 16777216;

        // Divide by 32, mix, prune.
        $secret = (intdiv( $secret, 32 ) ^ $secret) % 16777216;

        //Multiply by 2048, mix, prune.
        $secret = ( ( $secret * 2048 ) ^ $secret ) % 16777216;
    }

    return $secret;
}

function evolveOnce(int $secret): int{
    // Multiply by 64, mix, prune.
    $secret = (($secret * 64) ^ $secret) % 16777216;

    // Divide by 32, mix, prune.
    $secret = (intdiv( $secret, 32 ) ^ $secret) % 16777216;

    //Multiply by 2048, mix, prune.
    return ( ( $secret * 2048 ) ^ $secret ) % 16777216;
}

$part1 = array_sum(array_map('evolveSecret', $initialSecrets));

$patterns = [];

foreach ($initialSecrets as $initialSecret ) {
    $secret = $initialSecret;
    $price = $initialSecret % 10; // Last number determines the price
    $differences = [];

    for ( $iteration = 0; $iteration < 2000; $iteration ++ ) {
        $secret = evolveOnce($secret);
        $secretPrice = $secret % 10;

        $differences[] = [ 'difference' => $secretPrice - $price, 'price' => $secretPrice ];
        $price = $secretPrice;
    }

    $cache = [];

    for ( $i = 0; $i < count( $differences ) - 3; $i ++ ) {
        $sequence    = array_column( array_slice( $differences, $i, 4 ), 'difference' );
        $final_price = $differences[ $i + 3 ]['price'];
        $key         = implode( ',', $sequence );

        if ( ! in_array( $key, $cache, true ) ) {
            $cache[] = $key;

            if (!array_key_exists($key, $patterns)) {
                $patterns[ $key ] = $final_price;
            } else {
                $patterns[ $key ] += $final_price;
            }
        }
    }
}


echo "Part 1:" . $part1 . PHP_EOL;
echo "Part 2:" .  max( $patterns ) . PHP_EOL;