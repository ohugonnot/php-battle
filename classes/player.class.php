<?php
class Player
{
    public string $name;
    public float $power;
    public float $mana;
    public float $health;
    public string $comment;

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
        $this->comment = "$this->name attaque $player->name et lui inflige $damage de dégats  \r \n";
        // $damage = $player->power / 20;
        // $this->health -= $damage;
        // echo " \r \n $player->name réplique et inflige $damage de dégat à $this->name.";
    }


    public function cure()
    {
        if ($this->mana > 0) {

            $this->health += (($this->mana / 20) + 10);
            $this->mana /= 4;
            $this->mana -= 1;
            $this->comment = "$this->name s'est soigné";
            return $this;
        } else {
            $this->comment = "$this->name n'a plus assez de mana pour se soigner";
            return false;
        }
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
