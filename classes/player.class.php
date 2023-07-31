<?php
class Player
{
    public string $name;
    public int $power;
    public int $mana;
    public int $health;

    public function __construct(string $name, int $power, int $mana, int $health)
    {
        $this->name = $name;
        $this->power = $power;
        $this->mana = $mana;
        $this->health = $health;
    }
    public function attack(Player $player)
    {
        $damage = $this->power / 20;
        echo "$this->name attaque $player->name et lui inflige $damage de dégats  \r \n";
        $player->loseHealth($damage);
        $player->counterAttack($this);
        dump($player);
        return $player->health;
    }
    public function counterAttack($player)
    {
        $damage = $player->power / 20;
        $this->health -= $damage;
        echo " \r \n $player->name réplique et inflige $damage de dégat à $this->name.";
    }

    public function loseHealth($damage)
    {
        $this->health -= $damage;
        return $this;
    }

    public function cure($mana)
    {
        $this->health += ($mana / 20);
        $mana /= 3;
        return $this;
    }
    public function die()
    {
        if ($this->health <= 0) {
            $this->lose();
        }
    }
    public function lose()
    {
        echo 'You lose ahahah';
    }
}


class Fight
{
    public object $player1;
    public object $player2;

    public function __construct(Player $player1, Player $player2 = null)
    {
        $this->player1 = $player1;
        $this->player2 = $player2;
    }
    public function watchHealth()
    {
        if ($this->player1->health <= 0 || $this->player2->health <= 0) {
        }
    }
    public function stopMatch()
    {
    }
}
