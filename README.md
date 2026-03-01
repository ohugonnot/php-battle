# PHP Battle

Exercice pédagogique conçu et donné à mes étudiants lors de mon activité de formateur développeur web. Objectif : découvrir la POO PHP de façon ludique via un jeu de combat de personnages. Chaque personnage a ses propres stats (force, défense, vitesse) et attaques spéciales. Les combats se résolvent tour par tour avec calcul de dégâts et effets de statut.

## Fonctionnalités

- Système de personnages extensible (héritage, interfaces)
- Mécanique de combat tour par tour avec aléatoire pondéré
- Statistiques de combat et historique des parties
- Interface web légère avec effets sonores

## Stack

- PHP 8 — POO, héritage, interfaces
- MySQL via PDO pour la persistance des personnages et des stats
- Composer pour les dépendances

## Lancer le projet

```bash
composer install
# Importer le schéma SQL
mysql -u root -p < schema.sql
php -S localhost:8000
```
