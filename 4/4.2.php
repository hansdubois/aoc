<?php

declare(strict_types=1);

$input = explode(PHP_EOL, file_get_contents(__DIR__ . '/input.txt'));

$drawnNumbers = array_map('intval', explode(',', trim($input[0])));
/**
 * @var $cards Card[]
 */
$cards = [];

// First two do not count, get 5 lines per iteration
for($i = 2; $i < count($input); $i += 6) {
    $cards[] = Card::makeCard(array_slice($input, $i, 5));
}

$winners = [];
$lastWinningNumber = 0;

foreach ($drawnNumbers as $number) {
    array_walk($cards, function (Card $card) use ($number) {
        $card->draw($number);
    });

    foreach ($cards as $i => $card) {
        if ($card->isWinner()) {
            $winners[] = $card;
            unset($cards[$i]);

            if(count($cards) === 0)
            {
                echo end($winners)->geTotalUnmarked() * $number;

                echo "done!";
                exit();
            }


        }
    }
}


class Card {
    /**
     * @var array<int, array>
     */
    public $numbers = [];

    private $horizontalRows = [
        [0,1,2,3,4],
        [5,6,7,8,9],
        [10,11,12,13,14],
        [15,16,17,18,19],
        [20,21,22,23,24],
    ];

    private $verticalRows = [
        [0, 5, 10, 15, 20],
        [1, 6, 11, 16, 21],
        [2, 7, 12, 17, 22],
        [3, 8, 13, 18, 23],
        [4, 9, 14, 19, 24]
    ];

    public static function makeCard(array $rows): self
    {
        $card = new self();

        $card->numbers = array_reduce($rows, function ($carry, $row): array {
            // Clean double spaces
            $clean = ltrim(str_replace("  ", " ", $row));

            return array_merge($carry, array_map(function (string $number) {
                return new CardNumber((int)$number, false);
            }, explode(" ", $clean)));
        }, []);

        return $card;
    }

    public function geTotalUnmarked(): int
    {
        return array_reduce($this->numbers, function (int $carry, CardNumber $item) {
            return $item->isMarked() ? $carry : $carry + $item->getNumber();
        }, 0);
    }

    public function draw($drawnNumber)
    {
        array_walk($this->numbers, function (CardNumber $cardNumber) use ($drawnNumber) {
            if ($cardNumber->getNumber() === $drawnNumber) {
                $cardNumber->mark();
            }
        });
    }

    public function isWinner(): bool
    {
        foreach ($this->horizontalRows as $row)
        {
            $counter = 0;

            foreach ($row as $position)
            {
                if ($this->numbers[$position]->isMarked()) {
                    $counter++;
                }
            }

            if ($counter === 5) {
                return true;
            }
        }

        foreach ($this->verticalRows as $row)
        {
            $counter = 0;

            foreach ($row as $position)
            {
                if ($this->numbers[$position]->isMarked()) {
                    $counter++;
                }
            }

            if ($counter === 5) {
                return true;
            }
        }
        return false;
    }

    public function __toString()
    {
        $return = '';

        for ($i = 1; $i <= count($this->numbers); $i++) {
            $return .= $this->numbers[$i - 1]->getNumber();

            $return .= $this->numbers[$i - 1]->isMarked() ? '* ' : '  ';

            if ($i % 5 == 0) {
                $return .= PHP_EOL;
            }
        }

        return $return;
    }
}

class CardNumber
{
    private int $number;
    private bool $marked;

    public function __construct(int $number, bool $marked)
    {
        $this->number = $number;
        $this->marked = $marked;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @return bool
     */
    public function isMarked(): bool
    {
        return $this->marked;
    }

    public function mark(): void
    {
        $this->marked = true;
    }
}