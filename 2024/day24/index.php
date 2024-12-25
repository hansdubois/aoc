<?php
[$registryInput, $instructionsInput] = explode(PHP_EOL . PHP_EOL, file_get_contents(__DIR__ . "/input.txt"));

$registry = [];
$instructionList = [];

foreach (explode(PHP_EOL, $registryInput) as $input) {
    [$key, $value] = explode(': ', $input);

    $registry[$key] = (int)$value;
}

foreach (explode(PHP_EOL, $instructionsInput) as $instructionLine) {
    [$instruction, $target] = explode(' -> ', $instructionLine);
    [$left, $operation, $right] = explode(' ', $instruction);

    $instructionList[$instructionLine] = [
        'operation' => $operation,
        'left' => $left,
        'right' => $right,
        'target' => $target
    ];
}

while (count($instructionList) > 0) {
    foreach ($instructionList as $instructionKey => $instruction) {
        if (array_key_exists($instruction['left'], $registry) && array_key_exists($instruction['right'], $registry)) {
            if ($instruction['operation'] ===  "AND") {
                $return = $registry[$instruction['left']] === 1 &&  $registry[$instruction['right']] === 1 ? 1 : 0;

                $registry[$instruction['target']] = $return;

                unset($instructionList[$instructionKey]);
            } else if ($instruction['operation'] ===  "OR") {
                $return = $registry[$instruction['left']] === 1 || $registry[$instruction['right']] === 1 ? 1 : 0;

                $registry[$instruction['target']] = $return;

                unset($instructionList[$instructionKey]);
            } else if ($instruction['operation'] ===  "XOR") {
                $return = $registry[$instruction['left']] != $registry[$instruction['right']] ? 1 : 0;

                $registry[$instruction['target']] = $return;

                unset($instructionList[$instructionKey]);
            }
        }
    }
}

$items = [];

ksort($registry);
var_dump($registry);

foreach ($registry as $key => $value)
{
    if (str_starts_with($key, "z")) {
        $items[] = $value;
    }
}

echo "Part 1: " . implode("", array_reverse($items)) . PHP_EOL;

