<?php

$ticks = 2503;

//Rudolph can fly 22 km/s for 8 seconds, but then must rest for 165 seconds.
//Cupid can fly 8 km/s for 17 seconds, but then must rest for 114 seconds.
//Prancer can fly 18 km/s for 6 seconds, but then must rest for 103 seconds.
//Donner can fly 25 km/s for 6 seconds, but then must rest for 145 seconds.
//Dasher can fly 11 km/s for 12 seconds, but then must rest for 125 seconds.
//Comet can fly 21 km/s for 6 seconds, but then must rest for 121 seconds.
//Blitzen can fly 18 km/s for 3 seconds, but then must rest for 50 seconds.
//Vixen can fly 20 km/s for 4 seconds, but then must rest for 75 seconds.
//Dancer can fly 7 km/s for 20 seconds, but then must rest for 119 seconds.

$reindeers = [
    new reindeer('Rudolph', 22, 8, 165),
    new reindeer('Cupid', 8, 17, 114),
    new reindeer('Prancer', 18, 6, 103),
    new reindeer('Donner', 25, 6, 145),
    new reindeer('Dasher', 11, 12, 125),
    new reindeer('Comet', 21, 6, 121),
    new reindeer('Blitzen', 18, 3, 50),
    new reindeer('Vixen', 20, 4, 75),
    new reindeer('Dancer', 7, 20, 119),
];

$leaderBoard = [
    'Rudolph' => 0,
    'Cupid' => 0,
    'Prancer' => 0,
    'Donner' => 0,
    'Dasher' => 0,
    'Comet' => 0,
    'Blitzen' => 0,
    'Vixen' => 0,
    'Dancer' => 0,
];

for ($i = 0; $i < $ticks; $i++)
{
    foreach ($reindeers as $reindeer)
    {
        $reindeer->tick();
    }

    $leaderDistance = max(array_map(fn($reindeer): int => $reindeer->getDistance(), $reindeers));

    foreach ($reindeers as $reindeer)
    {
        if ($reindeer->getDistance() === $leaderDistance)
        {
            $leaderBoard[$reindeer->getName()] += 1;
        }
    }
}

$distances = array_map(fn($reindeer): int => $reindeer->getDistance(), $reindeers);

var_dump(max($distances));
var_dump($leaderBoard);

class reindeer{
    private int $distance = 0;
    private string $state = 'fly';
    private int $stateSeconds = 0;

    public function __construct(private string $name, private int $speed, private  int $flySeconds, private int $rest)
    {
    }

    public function tick()
    {
        if ($this->state === 'fly') {
            $this->stateSeconds += 1;

            $this->distance += $this->speed;

            if ($this->stateSeconds === $this->flySeconds) {
                $this->state = 'rest';
                $this->stateSeconds = 0;
            }


        } else if ($this->state === 'rest') {
            $this->stateSeconds += 1;

            if ($this->stateSeconds === $this->rest) {
                $this->state = 'fly';
                $this->stateSeconds = 0;
            }
        }
    }

    public function getDistance(): int
    {
        return $this->distance;
    }

    public function getName(): string
    {
        return $this->name;
    }
}