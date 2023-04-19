<?php

enum Spells {
    case MagicMissile;
    case Drain;
    case Shield;
    case Poison;
    case Recharge;
}

$stateQueue = new SplQueue();
$stateQueue->enqueue(new state());
$bestResult = PHP_INT_MAX;

while($stateQueue->count() > 0)
{
    /** @var state $state */
    $state = $stateQueue->dequeue();

    foreach ($state->getPossibleNextSpells() as $spell)
    {
        $newState = state::fromState($state);
        $result = $newState->takeTurn($spell);

        if ($result === 'continue')
        {
            $stateQueue->enqueue($newState);
        } else if ($result === 'win') {
            echo "asdf";
            exit();
        }
    }
}

var_dump($bestResult);


class spell {
    public function __construct(
        public string $name,
        public int $cost,
        public int $damage,
        public int $heal,
        public int $armor,
        public int $duration
    ) {}
}

class state
{
    public int $bossHealth = 58;
    public int $bossDamage = 9;

    public int $playerHealth = 50;
    public int $playerMana = 500;
    public int $playerArmor = 0;

    public int $manaSpend = 0;

    public static function fromState(state $state) : self
    {
        $new = clone $state;
        $new->activeSpells = $state->activeSpells;

        return $new;
    }

    /**
     * @var $activeSpells activeSpell[]
     */
    public array $activeSpells = [];

    public function getPossibleNextSpells()
    {
        $allSpells = AllSpells::getAllSpells();

        $active = $this->activeSpells;
        $playerMana = $this->playerMana;


        var_dump(array_filter($allSpells, function (spell $spell) use ($active, $playerMana) {
            return !array_key_exists($spell->name, $active) && $playerMana > $spell->cost;
        }));


        return array_filter($allSpells, function (spell $spell) use ($active, $playerMana) {
            return !array_key_exists($spell->name, $active) && $playerMana > $spell->cost;
        });
    }

    public function takeTurn(spell $spell): string
    {
        // Always deduct mana
        $this->manaSpend += $spell->cost;
        $this->playerMana -= $spell->cost;

        if ($spell->duration > 0 )
        {
            $this->activeSpells[$spell->name] = new activeSpell($spell);
        } else {
            $this->bossHealth -= $spell->damage;
            $this->playerHealth += $spell->heal;
        }

        $this->runActiveSpells();

        $damage = $this->bossDamage - $this->playerArmor;

        // If damage is below 1, damage = 1
        if ($damage < 1)
        {
            $damage = 1;
        }

        $this->playerHealth -= $damage;

        if ($this->playerHealth <= 0)
        {
            return 'loss';
        }

        $this->runActiveSpells();

        echo $this->bossHealth;

        if ($this->bossHealth <= 0) {
            return 'win';
        }

        echo sprintf("Boss attacks for %d damage!", $damage) . PHP_EOL;

        return "continue";
    }

    public function runActiveSpells()
    {
        foreach ($this->activeSpells as $activeSpell)
        {
            if ($activeSpell->getSpell() == Spells::Recharge) {
                $this->playerMana += $activeSpell->getSpell()->heal;
            } else {
                $this->playerHealth += $activeSpell->getSpell()->heal;
            }

            $this->playerArmor = $activeSpell->getSpell()->armor;
            $this->bossHealth -= $activeSpell->getSpell()->damage;

            $activeSpell->deductTurn();
        }

        $this->activeSpells = array_filter($this->activeSpells, function (activeSpell $spell) { return $spell->getTurns() > 0;});
    }
}

class activeSpell
{
    private int $turns;
    private spell $spell;

    public function __construct(spell $spell)
    {
        $this->turns = $spell->duration;
        $this->spell = $spell;
    }

    /**
     * @return int
     */
    public function getTurns(): int
    {
        return $this->turns;
    }

    public function deductTurn()
    {
        $this->turns -= 1;
    }

    /**
     * @return spell
     */
    public function getSpell(): spell
    {
        return $this->spell;
    }
}

class AllSpells {
    /**
     * @return spell[]
     */
    public static function getAllSpells(): array
    {
        return [
            Spells::MagicMissile->name => new spell(Spells::MagicMissile->name, 53, 4, 0, 0, 0),
            Spells::Drain->name => new spell(Spells::Drain->name, 73, 2, 2, 0, 0),
            Spells::Shield->name => new spell(Spells::Shield->name, 113, 0, 0, 7, 6),
            Spells::Poison->name => new spell(Spells::Poison->name, 173, 3, 0, 0, 6),
            Spells::Recharge->name => new spell(Spells::Recharge->name, 229, 0, 101, 0, 5),
        ];
    }
}