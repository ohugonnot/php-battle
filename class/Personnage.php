<?php

class Personnage
{
    public string $name;
    public int $sante = 100;
    public int $attaque = 50;
    public int $mana = 100;

    public bool $is_alive = true;

    public function __construct(string $name, int $attaque, int $mana, int $sante)
    {
        $this->name = $name;
        $this->attaque = $attaque;
        $this->mana = $mana;
        $this->sante = $sante;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function attaque(Personnage $personnage)
    {
        $personnage->sante -= $this->attaque;
        $_SESSION["combats"][] = $this->name . " attaque " . $personnage->name . " et lui infligue " . $this->attaque . " de dégats";
        if ($personnage->sante <= 0) {
            $personnage->sante = 0;
            $personnage->is_alive = false;
        }
    }
}