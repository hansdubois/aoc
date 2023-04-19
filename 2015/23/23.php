<?php

$registers = [
    'a' => 1,
    'b' => 0
];

$input = file_get_contents('input.txt');
$lines = explode("\n", $input);

$instructions = [];

foreach ($lines as $line)
{
    if (preg_match("/(inc|tpl|hlf)\s(a|b)/", $line, $matches)) {
        $instructions[] = new instruction($matches[1], $matches[2]);
    } elseif (preg_match("/jmp (\+.*|\-.*)/", $line, $matches)) {
        $instructions[] = new instruction('jmp', '', $matches[1]);
    } elseif (preg_match("/(jio|jie)\s(a|b),\s(\+.*)/", $line, $matches))
    {
        $instructions[] = new instruction($matches[1], $matches[2], $matches[3]);
    }
}
$i = 0;
while ($i < count($instructions))
{
    $instruction = $instructions[$i];

    switch($instruction->instruction) {
        case "hlf":
            $registers[$instruction->registry] /= 2;
            $i++;
            break;

        case "tpl":
            $registers[$instruction->registry] *= 3;
            $i++;
            break;

        case "inc":
            $registers[$instruction->registry] += 1;
            $i++;
            break;

        case "jmp":
            $i += (int)$instruction->value;
            break;

        case "jio":
            if ($registers[$instruction->registry] == 1) {
                $i += (int)$instruction->value;
            } else {
                $i++;
            }

            break;

        case "jie":
            if ($registers[$instruction->registry] % 2 === 0) {
                $i += (int)$instruction->value;
            } else {
                $i++;
            }

            break;
    }
}

var_dump($registers);

class instruction
{
    public function __construct(
        public string $instruction,
        public string $registry = '',
        public string $value = ''
    ) {}
}
