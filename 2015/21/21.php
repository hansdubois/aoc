<?php
$weapons = [
    new item('Dagger', 8, 4, 0),
    new item('Shortsword', 10, 5, 0),
    new item('Warhammer', 25, 6, 0),
    new item('Longsword', 40, 7, 0),
    new item('Greataxe', 78, 8, 0)
];

$armors = [
    new item('Leather', 13, 0, 1),
    new item('Chainmail', 31, 0, 2),
    new item('Splintmail', 53, 0, 3),
    new item('Bandedmail', 75, 0, 4),
    new item('Platemail', 102, 0, 5),
];

$ringItems = [
    new item('Damage +1', 25, 1, 0),
    new item('Damage +2', 50, 2, 0),
    new item('Damage +3', 100, 3, 0),
    new item('Defense +1', 20, 0, 1),
    new item('Defense +2', 40, 0, 2),
    new item('Defense +3', 80, 0, 3)
];

$ringCombinations = [
    [],
    [0],
    [1],
    [2],
    [3],
    [4],
    [5],
    [0,1],
    [0,2],
    [0,3],
    [0,4],
    [0,5],
    [1,2],
    [1,3],
    [1,4],
    [1,5],
    [2,3],
    [2,4],
    [2,5],
    [3,4],
    [3,5],
    [4,5]
];

$combinations = [];

foreach ($weapons as $weapon)
{
    foreach ($armors as $armor)
    {
        foreach ($ringCombinations as $rings)
        {
            $combinations[] = [$weapon, $armor, ...array_map(function ($ring) use ($ringItems) { return $ringItems[$ring];}, $rings)];
        }
    }
}

$numbers = array_map(function(array $combo) {
    $armor = $cost = $damage = 0;

    /** @var item $item */
    foreach ($combo as $item)
    {
        $armor += $item->armor;
        $cost += $item->cost;
        $damage += $item->damage;
    }

    return [
        'armor' => $armor,
        'cost' => $cost,
        'damage' => $damage,
    ];

}, $combinations);

$wins = [];
$losses = [];

foreach ($numbers as $game) {
    $player = 100;

    $boss = 103;
    $bossDamage = 9 - $game['armor'];
    $playerDamage = $game['damage']  - 2;

    $gameEnded = false;

    while(!$gameEnded)
    {
        $boss -= $playerDamage;

        if ($boss <= 0)
        {
            if ($player > 0) {
                echo sprintf("Player won; Cost: %s", $game['cost']) . PHP_EOL;
                $wins[] = $game['cost'];
            }

            $gameEnded = true;
        }

        $player -= $bossDamage;

        if ($player <= 0) {
            $gameEnded = true;

            $losses[] =  $game['cost'];
        }
    }

}

sort($wins);
rsort($losses);

echo "All scenario's played" . PHP_EOL;
echo "Victory with least cost : " . $wins[0] . PHP_EOL;
echo "Loss with most cost : " . $losses[0] . PHP_EOL;

class item {
    public function __construct(public string $name, public int $cost, public int $damage, public int $armor = 0)
    {}
}