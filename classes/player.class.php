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
        $damage = $this->power;
        $player->loseHealth($damage);
    }

    public function loseHealth($damage)
    {
        $this->health -= $damage;
    }

    public function cure($mana)
    {
        $this->health +=;
    }


    public function test()
    {
        echo 'test';
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
    public function countHealth()
    {
    }
    public function stopMatch()
    {
    }
}
