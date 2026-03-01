# PHP Battle — Solution

Correction de l'exercice POO PHP. Ce README présente **deux approches** pour résoudre le même problème, de la plus simple à la plus structurée.

> La branche `exercice` contient le point de départ si tu veux faire l'exercice toi-même avant de lire la correction.

---

## Approche 1 — Procédurale (sans classe)

La version la plus directe : des fonctions PHP qui manipulent des tableaux.

**Fichiers :** `lib.php`, `index.php`

```php
// Un personnage = un tableau PHP
$player = [
    "name"   => "Héros",
    "sante"  => 100,
    "attaque" => 30,
    "mana"   => 100,
];

// Une action = une fonction globale
function attaque(): void
{
    [$player, $adversaire, $combats] = getInfoInSession();
    $adversaire['sante'] = max(0, $adversaire['sante'] - $player["attaque"]);
    $combats[] = $player["name"] . " attaque et inflige " . $player["attaque"] . " dégâts.";
    saveState($player, $adversaire, $combats);
}
```

**Ce que ça fait bien :** simple à lire, peu de code, fonctionne.

**Ce que ça fait mal :**
- Les données (`$player["sante"]`) et les actions (`attaque()`) sont séparées — aucun lien entre les deux
- Si on veut ajouter un deuxième type de personnage (magicien, guerrier...), il faut dupliquer les fonctions
- Impossible de réutiliser ce code dans un autre projet sans tout embarquer

---

## Approche 2 — Orientée objet (avec classe)

On regroupe les données **et** les actions dans une classe.

**Fichier :** `Personnage.php`

```php
class Personnage
{
    public string $nom;
    public int $sante;
    public int $attaque;
    public int $mana;

    public function __construct(string $nom, int $sante, int $attaque, int $mana)
    {
        $this->nom     = $nom;
        $this->sante   = $sante;
        $this->attaque = $attaque;
        $this->mana    = $mana;
    }

    // L'action est dans la classe — elle sait elle-même comment attaquer
    public function attaquer(Personnage $cible): string
    {
        $cible->recevoirDegats($this->attaque);
        return $this->nom . " attaque " . $cible->nom . " et lui inflige " . $this->attaque . " dégâts.";
    }

    public function recevoirDegats(int $degats): void
    {
        $this->sante = max(0, $this->sante - $degats);
    }

    public function estVivant(): bool
    {
        return $this->sante > 0;
    }
}

// Utilisation
$hero = new Personnage("Héros", 100, 30, 100);
$ennemi = new Personnage("Gobelin", 60, 15, 0);

while ($hero->estVivant() && $ennemi->estVivant()) {
    echo $hero->attaquer($ennemi) . "\n";
    if ($ennemi->estVivant()) {
        echo $ennemi->attaquer($hero) . "\n";
    }
}

echo $hero->estVivant() ? $hero->nom . " gagne !" : $ennemi->nom . " gagne !";
```

**Ce que la POO apporte ici :**

| Problème procédural | Solution POO |
|---------------------|--------------|
| Données et actions séparées | Tout est dans la classe |
| `$player["sante"]` — aucune garantie sur le type | `$personnage->sante` — typé `int` |
| Impossible de faire `$player->soigner()` | `$hero->soigner()` — l'objet sait se soigner |
| Ajouter un Magicien = copier toutes les fonctions | `class Magicien extends Personnage` — on hérite |

---

## Fichiers de la solution complète

```
Personnage.php      ← classe POO (approche 2)
lib.php             ← fonctions procédurales (approche 1)
index.php           ← interface web, utilise lib.php
repository.php      ← persistance BDD (PDO)
statistiques.php    ← stats des combats
```

## Lancer le projet

### Avec Docker (recommandé — aucune installation requise)

```bash
composer install
docker compose up
```

Puis ouvrir http://localhost:8000. MySQL démarre automatiquement avec le schéma.

### Sans Docker (PHP + MySQL déjà installés)

```bash
composer install
# Importer le schéma dans MySQL
mysql -u root < schema.sql
# Lancer le serveur PHP
php -S localhost:8000
```
