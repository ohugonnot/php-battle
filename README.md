# PHP Battle — Solution

Correction de l'exercice POO PHP : jeu de combat de personnages tour par tour.

> La branche `exercice` contient le point de départ si tu veux faire l'exercice toi-même.

## Installation

```bash
composer install
php -S localhost:8000
```

## Ce qui a été implémenté

- Classe `Personnage` avec gestion des PV, attaque, mana et dégâts
- Combat tour par tour avec résolution aléatoire pondérée
- Persistance des personnages et historique des combats via PDO/MySQL
- Statistiques de combat par personnage (`statistiques.php`)
- Effets sonores (Hadouken, Fatality) pour l'ambiance

## Stack

- PHP 8 — POO, PDO
- MySQL pour la persistance
- Composer pour l'autoloading
