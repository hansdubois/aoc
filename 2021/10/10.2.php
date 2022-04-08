<?php
declare(strict_types=1);

$lines = array_map(fn(string $line) => new Line($line), explode(PHP_EOL, file_get_contents(__DIR__ . '/input.txt')));
$lines = array_filter($lines, fn(Line $line) => !$line->isCorrupted());

$completions = array_map(fn(Line $line) => $line->getLineCompletion(), $lines);

var_dump($completions);

$scores = [
    ')' => 1,
    ']' => 2,
    '}' => 3,
    '>' => 4,
];

$calculations = [];
foreach ($completions as $completion)
{
    $calc = 0;
    $charlist = str_split($completion);

    foreach($charlist as $char) {
        $calc *= 5;
        $calc += $scores[$char];
    }

    $calculations[] = $calc;
}
sort($calculations);
var_dump($calculations);



class Line {
    private array $charPairs = ['(' => ')', '[' => ']', '{' => '}', '<' => '>'];

    private string $line;

    public function __construct(string $line)
    {
        $this->line = $line;
    }

    public function __toString(): string
    {
        return $this->line;
    }

    public function getLineCompletion(): string
    {
        $openingCharsInLine = [];
        $chars = str_split($this->line);

        foreach ($chars as $char) {
            if ($this->isOpenChar($char)) {
                $openingCharsInLine[] = $char;

                continue;
            }

            if ($this->isClosingChar($char))
            {
                array_pop($openingCharsInLine);
            }
        }
        krsort($openingCharsInLine);

        return str_replace(array_keys($this->charPairs), array_values($this->charPairs), implode($openingCharsInLine));
    }

    private function isOpenChar(string $char): bool
    {
        return in_array($char, array_keys($this->charPairs));
    }

    private function isClosingChar(string $char): bool
    {
        return in_array($char, array_values($this->charPairs));
    }
}