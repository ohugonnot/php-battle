# PHP Battle

Exercice pédagogique conçu et donné à mes étudiants lors de mon activité de formateur développeur web.

**Objectif** : découvrir la POO PHP de façon ludique via un jeu de combat de personnages tour par tour.

## Comment utiliser ce repo

| Branche | Contenu |
|---------|---------|
| [`exercice`](../../tree/exercice) | Point de départ : interface fournie, classes à créer |
| [`solution`](../../tree/solution) | Correction complète avec persistance BDD et statistiques |

> Commence par la branche `exercice`, consulte `solution` uniquement si tu es bloqué.

## L'exercice en bref

L'interface de combat est fournie (`index.php`, `navbar.php`). Tu dois créer :

- **`Personnage`** — propriétés (nom, PV, attaque, mana), méthodes (`attaquer()`, `recevoirDegats()`, `estVivant()`)
- La logique de **combat tour par tour** jusqu'à ce qu'un personnage tombe à 0 PV
- **Bonus** : persister les personnages et les stats en base de données

## Stack

- PHP 8 — POO
- MySQL / PDO pour la persistance (bonus)
- Composer pour l'autoloading
