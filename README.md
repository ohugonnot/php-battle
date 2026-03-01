# PHP Battle — Exercice

Exercice pédagogique conçu pour découvrir la **POO en PHP** de façon ludique : un jeu de combat de personnages.

## Comment utiliser ce repo

- **Branche `exercice`** (ici) — point de départ : interface et formulaire fournis, classes à créer
- **Branche `solution`** — correction complète

## Installation

```bash
composer install
php -S localhost:8000
```

## Objectif

L'interface de combat est déjà fournie (`index.php`, `navbar.php`). Tu dois :

1. Créer une classe `Personnage` avec les propriétés : nom, points de vie, attaque, mana
2. Implémenter les méthodes : `attaquer(Personnage $cible)`, `estVivant()`, `recevoirDegats(int $degats)`
3. Gérer un combat tour par tour entre deux personnages jusqu'à ce que l'un d'eux tombe à 0 PV
4. Afficher le résultat du combat dans l'interface (vainqueur, déroulé des tours)
5. **Bonus** : persister les personnages et les statistiques de combat en base de données

## Stack

- PHP 8 — POO, héritage
- MySQL / PDO pour la persistance (bonus)
- Composer pour l'autoloading
