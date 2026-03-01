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
    public int $manaMax;    // Mana maximum — pour afficher la barre correctement

    public function __construct(string $nom, int $sante, int $attaque, int $mana, int $manaMax = 0)
    {
        $this->nom      = $nom;
        $this->sante    = $sante;
        $this->santeMax = $sante; // On mémorise la vie de départ
        $this->attaque  = $attaque;
        $this->mana     = $mana;
        $this->manaMax  = $manaMax > 0 ? $manaMax : $mana; // manaMax = mana initial si pas défini
    }

    /**
     * Retourne les dégâts de base, avec bonus de rage si HP < 30% du max.
     * La rage ajoute +20% de dégâts quand le personnage est au plus bas.
     */
    private function getDegatsBase(): int
    {
        $bonus = $this->sante < ($this->santeMax * 0.3) ? 1.2 : 1.0;
        return (int)($this->attaque * $bonus);
    }

    /**
     * Attaque un autre personnage et retourne un message décrivant l'action.
     * 15% de chance de coup critique (1.5x les dégâts).
     * La rage (+20%) est stackable avec le critique.
     */
    public function attaquer(Personnage $cible): string
    {
        $degats  = $this->getDegatsBase();
        $isCrit  = rand(1, 100) <= 15;
        if ($isCrit) {
            $degats = (int)($degats * 1.5);
        }
        $cible->recevoirDegats($degats);
        $msg = $this->nom . " attaque " . $cible->nom . " et lui inflige " . $degats . " dégâts";
        return $isCrit ? $msg . " (COUP CRITIQUE !)" : $msg . ".";
    }

    /**
     * Coup spécial : dépense 50 mana pour infliger 2x les dégâts de base.
     * Retourne une chaîne vide si mana insuffisant.
     */
    public function coupSpecial(Personnage $cible): string
    {
        if ($this->mana < 50) {
            return "";
        }
        $this->mana -= 50;
        $degats = $this->attaque * 2;
        $cible->recevoirDegats($degats);
        return $this->nom . " déchaîne une attaque dévastatrice sur " . $cible->nom . " pour " . $degats . " dégâts ! (COUP SPÉCIAL !)";
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
     * Régénère 5 mana par tour (sans dépasser manaMax).
     */
    public function regenererMana(): void
    {
        $this->mana = min($this->manaMax, $this->mana + 5);
    }

    /**
     * L'adversaire choisit aléatoirement entre attaquer ou se soigner.
     * 25% de chance de se soigner s'il lui reste du mana.
     * Si HP critique (< 30%) et mana >= 35, utilise un coup spécial (coût réduit à 35).
     */
    public function jouerTour(Personnage $cible): string
    {
        // Coup spécial adversaire si HP critique et mana suffisant
        if ($this->sante < ($this->santeMax * 0.3) && $this->mana >= 35) {
            $this->mana -= 35;
            $degats = (int)($this->attaque * 1.8);
            $cible->recevoirDegats($degats);
            return $this->nom . " déchaîne une attaque désespérée sur " . $cible->nom . " pour " . $degats . " dégâts ! (COUP SPÉCIAL !)";
        }

        if (rand(0, 100) <= 25 && $this->mana >= 25) {
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
