# PHP Battle

Jeu de combat de personnages en PHP orienté objet. Chaque personnage a ses propres stats (force, défense, vitesse) et attaques spéciales. Les combats se résolvent tour par tour avec calcul de dégâts et effets de statut.

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
