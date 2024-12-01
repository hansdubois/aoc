<?php

$lines = explode("\n", file_get_contents(__DIR__ . '/input.txt'));

$programs = [];
$programsObjs = [];
$linkedPrograms = [];

foreach ($lines as $line) {
    $parts = explode("->", $line);
    $programParts = explode(" ", trim($parts[0]));
    $program = $programParts[0];

    $weight = intval(str_replace(['(', ')'], ['', ''], $programParts[1]));

    $children = [];

    $programs[] = $program;

    if (count($parts) > 1) {
        $children = explode(", ", ltrim($parts[1]));

        $linkedPrograms = array_merge($children, $linkedPrograms);
    }

    $programsObjs[$program] = new Program($program, $weight, $children);
}

$diff = array_diff($programs, $linkedPrograms);
$start = array_pop($diff);

$startObj = $programsObjs[$start];

isBalanced($startObj->name);

function isBalanced(string $name)
{
    /** @var $programsObjs Program[] */
    global $programsObjs;

    $program = $programsObjs[$name];

    $weight = $program->weight;
    $childWeights = [];

    if (count($program->children) > 0) {
        foreach ($program->children as $child) {
            $result = isBalanced($child);
            $weight += $result;

            $childWeights[$child] = $result;
        }
    }

    // Check if all child weigh the same
    $valueCount = array_count_values($childWeights);

    if (count($valueCount) > 1) {
        echo "Oops! Found unbalanced" . PHP_EOL;

        $flipped = array_flip($valueCount);

        $first = array_pop($flipped);
        $second = array_pop($flipped);

        echo "Weight should be: " . $weight - abs($first - $second) . PHP_EOL;
    }

    return $weight;
}

class Program {
    public function __construct(
        public readonly string $name,
        public readonly int $weight,
        public readonly array $children,
    ) {}
}