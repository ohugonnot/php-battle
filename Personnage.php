<?php

/**
 * Classe Personnage — Version orientée objet du combat
 *
 * Cette classe encapsule toutes les données et actions d'un personnage.
 * Compare avec lib.php : là où lib.php manipule des tableaux PHP ($player["sante"]),
 * ici on manipule un objet ($personnage->sante) avec des méthodes dédiées.
 */
class Personnage
{
    public string $nom;
    public int $sante;
    public int $santeMax;   // Vie maximum — utile pour les soins (on ne peut pas dépasser son max)
    public int $attaque;
    public int $mana;

    public function __construct(string $nom, int $sante, int $attaque, int $mana)
    {
        $this->nom      = $nom;
        $this->sante    = $sante;
        $this->santeMax = $sante; // On mémorise la vie de départ
        $this->attaque  = $attaque;
        $this->mana     = $mana;
    }

    /**
     * Attaque un autre personnage et retourne un message décrivant l'action.
     *
     * Avantage POO : la logique "qui attaque qui" est dans la classe,
     * pas dispersée dans des fonctions globales.
     */
    public function attaquer(Personnage $cible): string
    {
        $cible->recevoirDegats($this->attaque);
        return $this->nom . " attaque " . $cible->nom . " et lui inflige " . $this->attaque . " dégâts.";
    }

    /**
     * Reçoit des dégâts. La vie ne peut pas descendre en dessous de 0.
     */
    public function recevoirDegats(int $degats): void
    {
        // max(0, ...) évite d'avoir une vie négative
        $this->sante = max(0, $this->sante - $degats);
    }

    /**
     * Se soigne de 25% de sa vie maximum si on a assez de mana.
     */
    public function soigner(): string
    {
        if ($this->mana < 25) {
            return $this->nom . " n'a plus assez de mana pour se soigner.";
        }

        $soin = (int) round(0.25 * $this->santeMax);
        // min(santeMax, ...) évite de dépasser sa vie de départ
        $this->sante = min($this->santeMax, $this->sante + $soin);
        $this->mana -= 25;

        return $this->nom . " se soigne et restaure " . $soin . " points de vie.";
    }

    /**
     * L'adversaire choisit aléatoirement entre attaquer ou se soigner.
     * 25% de chance de se soigner s'il lui reste du mana.
     */
    public function jouerTour(Personnage $cible): string
    {
        if (rand(0, 100) <= 25 && $this->mana > 0) {
            return $this->soigner();
        }
        return $this->attaquer($cible);
    }

    /**
     * Retourne true si le personnage est encore en vie.
     */
    public function estVivant(): bool
    {
        return $this->sante > 0;
    }
}
