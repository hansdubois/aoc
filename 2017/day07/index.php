<?php

$lines = explode("\n", file_get_contents(__DIR__ . '/input.txt'));

$programs = [];
$linkedPrograms = [];

foreach ($lines as $line) {
    $parts = explode("->", $line);

    $program = explode(" ", trim($parts[0]))[0];

    $programs[] = $program;

    if (count($parts) > 1) {
        $linked = explode(", ", ltrim($parts[1]));

        $linkedPrograms = array_merge($linked, $linkedPrograms);
    }
}

$diff = array_diff($programs, $linkedPrograms);
$start = array_pop($diff);

echo "Part 1: " . $start . PHP_EOL;

class program {
    public function __construct(
       public readonly string $name,
       public readonly int $weight,

    ) {}
}