<?php
class Player
{
    public string $name;
    public float $power;
    public float $mana;
    public float $health;

    public function __construct(string $name, int $power, int $mana, int $health)
    {
        $this->name = $name;
        $this->power = $power;
        $this->mana = $mana;
        $this->health = $health;
    }
    public function attack(Player $player)
    {
        $damage = $this->power / 4;
        $player->health -= $damage;
        echo "$this->name attaque $player->name et lui inflige $damage de dégats  \r \n";
        $this->getLifeStatus();
        // $damage = $player->power / 20;
        // $this->health -= $damage;
        // echo " \r \n $player->name réplique et inflige $damage de dégat à $this->name.";
    }


    public function cure()
    {
        $this->health += ($this->mana / 20);
        $this->mana /= 4;
        echo "$this->name s'est soigné";
        return $this;
    }

    public function getLifeStatus()
    {
        if ($this->health > 0) {
            return true;
        } else {
            echo 'you are dead';
            return false;
        }
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

    public function stopMatch()
    {
    }
}
