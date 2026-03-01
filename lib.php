<?php

require_once __DIR__ . '/Personnage.php';

function getInfoInSession(): array
{
    $player     = $_SESSION["player"] ?? null;
    $adversaire = $_SESSION["adversaire"] ?? null;
    $combats    = $_SESSION["combats"] ?? null;
    return [$player, $adversaire, $combats];
}

function saveState(?array $player, ?array $adversaire, array $combat = [], ?array $winner = null, ?array $fight = null): void
{
    $_SESSION["player"]     = $player;
    $_SESSION["adversaire"] = $adversaire;
    $_SESSION["combats"]    = $combat;
    if ($fight) {
        $_SESSION["fight"] = $fight;
    }
    updateLog($combat);
    if ($winner) {
        $_SESSION["winner"] = $winner["name"];
        updateWinner($winner);
    }
}

function removeInfoInSession(): void
{
    session_destroy();
    $_SESSION = [];
}

function checkErrorsForm(): array
{
    $formErrors = [];
    $player     = $_POST['player'];
    $adversaire = $_POST['adversaire'];
    $player["id"]           = $player["id"] ?? "";
    $player["name"]         = trim($player["name"]);
    $player["sante"]        = intval($player["sante"]);
    $player["mana"]         = intval($player["mana"]);
    $player["attaque"]      = intval($player["attaque"]);
    $adversaire["id"]       = $adversaire["id"] ?? "";
    $adversaire["name"]     = trim($adversaire["name"]);
    $adversaire["sante"]    = intval($adversaire["sante"]);
    $adversaire["mana"]     = intval($adversaire["mana"]);
    $adversaire["attaque"]  = intval($adversaire["attaque"]);

    $format = '%s %s doit être superieur à %d.';
    if (($player["id"] ?? "") === "") {
        if ($player["attaque"] <= 0) {
            $formErrors['player']['attaque'] = sprintf($format, "L'attaque", "du joueur", 0);
        }
        if ($player["mana"] <= 0) {
            $formErrors['player']["mana"] = sprintf($format, "Le mana", "du joueur", 0);
        }
        if ($player["sante"] <= 0) {
            $formErrors['player']["sante"] = sprintf($format, "La santé", "du joueur", 0);
        }
    }

    if (($adversaire["id"] ?? "") === "") {
        if ($adversaire["attaque"] <= 0) {
            $formErrors['adversaire']["attaque"] = sprintf($format, "L'attaque", "de l'adversaire", 0);
        }
        if ($adversaire["mana"] <= 0) {
            $formErrors['adversaire']["mana"] = sprintf($format, "Le mana", "de l'adversaire", 0);
        }
        if ($adversaire["sante"] <= 0) {
            $formErrors['adversaire']["sante"] = sprintf($format, "La santé", "de l'adversaire", 0);
        }
    }

    return [$formErrors, $player, $adversaire];
}

/**
 * Construit un objet Personnage à partir du tableau de session.
 */
function creerPersonnage(array $data): Personnage
{
    return new Personnage(
        $data['name'],
        $data['sante'],
        $data['attaque'],
        $data['mana'],
        $data['manaMax'] ?? $data['mana']
    );
}

/**
 * Re-synchronise le tableau de session à partir de l'état de l'objet Personnage.
 * On ne touche qu'aux champs qui peuvent changer pendant un tour (sante, mana).
 */
function personnageToArray(Personnage $p, array $original): array
{
    return array_merge($original, [
        'sante' => $p->sante,
        'mana'  => $p->mana,
    ]);
}

function attaque(): void
{
    list($player, $adversaire, $combats) = getInfoInSession();

    $p = creerPersonnage($player);
    $a = creerPersonnage($adversaire);

    $p->regenererMana();
    $msg      = $p->attaquer($a);
    $combats[] = $msg;

    $player     = personnageToArray($p, $player);
    $adversaire = personnageToArray($a, $adversaire);
    saveState($player, $adversaire, $combats);

    if (!$a->estVivant()) {
        $combats[] = $p->nom . " a tué " . $a->nom . ", c'est la fin de la partie !";
        saveState($player, $adversaire, $combats, $player);
    } else {
        adversaireAction();
    }
}

function coupSpecial(): void
{
    list($player, $adversaire, $combats) = getInfoInSession();

    $p = creerPersonnage($player);
    $a = creerPersonnage($adversaire);

    $p->regenererMana();
    $msg = $p->coupSpecial($a);

    if ($msg === "") {
        // Mana insuffisant — on retombe sur une attaque normale
        $msg = $p->attaquer($a);
    }

    $combats[] = $msg;

    $player     = personnageToArray($p, $player);
    $adversaire = personnageToArray($a, $adversaire);
    saveState($player, $adversaire, $combats);

    if (!$a->estVivant()) {
        $combats[] = $p->nom . " a tué " . $a->nom . ", c'est la fin de la partie !";
        saveState($player, $adversaire, $combats, $player);
    } else {
        adversaireAction();
    }
}

function adversaireAction(): void
{
    list($player, $adversaire, $combats) = getInfoInSession();

    $p = creerPersonnage($player);
    $a = creerPersonnage($adversaire);

    $a->regenererMana();
    $msg      = $a->jouerTour($p);
    $combats[] = $msg;

    $player     = personnageToArray($p, $player);
    $adversaire = personnageToArray($a, $adversaire);

    $winner = null;
    if (!$p->estVivant()) {
        $combats[] = $a->nom . " a tué " . $p->nom . ", c'est la fin de la partie !";
        $winner = $adversaire;
    }

    saveState($player, $adversaire, $combats, $winner);
}

function soin(): void
{
    list($player, $adversaire, $combats) = getInfoInSession();

    $p = creerPersonnage($player);
    $a = creerPersonnage($adversaire);

    $p->regenererMana();
    $msg      = $p->soigner();
    $combats[] = $msg;

    $player     = personnageToArray($p, $player);
    $adversaire = personnageToArray($a, $adversaire);
    saveState($player, $adversaire, $combats);

    adversaireAction();
}
