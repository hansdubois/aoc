<?php
declare(strict_types=1);

$lines = array_map(fn(string $line) => new Line($line), explode(PHP_EOL, file_get_contents(__DIR__ . '/input.txt')));

$points = [
    ')' => 3,
    ']' => 57,
    '}' => 1197,
    '>' => 25137
];

$score = 0;


foreach ($lines as $line)
{
    $char = $line->getFirstCorrupted();
    if ($char !== '') {
        $score += $points[$char];
    }
}

var_dump($score);

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

    public function getFirstCorrupted(): string
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
                $openingChar = array_pop($openingCharsInLine);

                if ($char !== $this->charPairs[$openingChar]) {
                    return $char;
                }
            }
        }

        return '';
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