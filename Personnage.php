<?php

/**
 * Classe Personnage
 *
 * Un personnage possède un nom, des points de vie, une valeur d'attaque et du mana.
 * Tu dois compléter les méthodes ci-dessous.
 *
 * Pour tester rapidement sans interface web, tu peux exécuter ce fichier directement :
 *   php Personnage.php
 */
class Personnage
{
    public string $nom;
    public int $sante;
    public int $santeMax;  // Vie de départ — utile pour ne pas dépasser son maximum lors des soins
    public int $attaque;
    public int $mana;

    public function __construct(string $nom, int $sante, int $attaque, int $mana)
    {
        $this->nom      = $nom;
        $this->sante    = $sante;
        $this->santeMax = $sante;
        $this->attaque  = $attaque;
        $this->mana     = $mana;
    }

    /**
     * Attaque un autre personnage.
     *
     * - Appelle recevoirDegats() sur la cible avec la valeur d'attaque du personnage
     * - Retourne un message décrivant l'action, ex :
     *   "Héros attaque Gobelin et lui inflige 30 dégâts."
     */
    public function attaquer(Personnage $cible): string
    {
        // TODO
    }

    /**
     * Reçoit des dégâts.
     *
     * - Réduit $this->sante de la valeur $degats
     * - La vie ne peut pas descendre en dessous de 0
     * Indice : utilise max()
     */
    public function recevoirDegats(int $degats): void
    {
        // TODO
    }

    /**
     * Se soigne de 25% de sa vie maximum.
     *
     * - Coûte 25 mana
     * - Si le personnage n'a pas assez de mana, retourner un message d'erreur
     * - La vie ne peut pas dépasser $this->santeMax
     * Indice : utilise min() et round()
     */
    public function soigner(): string
    {
        // TODO
    }

    /**
     * Retourne true si le personnage est encore en vie (sante > 0).
     */
    public function estVivant(): bool
    {
        // TODO
    }
}

// --- Zone de test rapide ---
// Décommente les lignes suivantes pour tester ta classe directement dans le terminal
// php Personnage.php

/*
$hero   = new Personnage("Héros", 100, 30, 100);
$ennemi = new Personnage("Gobelin", 60, 15, 0);

while ($hero->estVivant() && $ennemi->estVivant()) {
    echo $hero->attaquer($ennemi) . "\n";
    if ($ennemi->estVivant()) {
        echo $ennemi->attaquer($hero) . "\n";
    }
}

echo ($hero->estVivant() ? $hero->nom : $ennemi->nom) . " gagne !\n";
*/
