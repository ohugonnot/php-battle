<?php

function getInfoInSession(): array
{
    $player = $_SESSION["player"] ?? null;
    $adversaire = $_SESSION["adversaire"] ?? null;
    $combats = $_SESSION["combats"] ?? null;
    return [$player, $adversaire, $combats];
}

function setInfoInSession(?array $player, ?array $adversaire, ?array $combat): void
{
    $_SESSION["player"] = $player;
    $_SESSION["adversaire"] = $adversaire;
    $_SESSION["combats"] = $combat;
}

function removeInfoInSession(): void
{
    session_destroy();
    $_SESSION = [];
}

function checkErrorsForm(): array
{
    $formErrors = [];
    $player = $_POST['player'];
    $adversaire = $_POST['adversaire'];
    $player["name"] = trim($player["name"]);
    $player["sante"] = intval($player["sante"]);
    $player["mana"] = intval($player["mana"]);
    $player["attaque"] = intval($player["attaque"]);
    $player["initial_life"] = $player["sante"];
    $adversaire["name"] = trim($adversaire["name"]);
    $adversaire["sante"] = intval($adversaire["sante"]);
    $adversaire["mana"] = intval($adversaire["mana"]);
    $adversaire["attaque"] = intval($adversaire["attaque"]);
    $adversaire["initial_life"] = $adversaire["sante"];

    $format = '%s %s doit être superieur à %d.';
    if ($player["attaque"] <= 0) {
        $formErrors['player']['attaque'] = sprintf($format, "L'attaque", "du joueur", 0);
    }
    if ($player["mana"] <= 0) {
        $formErrors['player']["mana"] = sprintf($format, "Le mana", "du joueur", 0);
    }
    if ($player["sante"] <= 0) {
        $formErrors['player']["sante"] = sprintf($format, "La santé", "du joueur", 0);
    }

    if ($adversaire["attaque"] <= 0) {
        $formErrors['adversaire']["attaque"] = sprintf($format, "L'attaque", "de l'adversaire", 0);
    }
    if ($adversaire["mana"] <= 0) {
        $formErrors['adversaire']["mana"] = sprintf($format, "Le mana", "de l'adversaire", 0);
    }
    if ($adversaire["sante"] <= 0) {
        $formErrors['adversaire']["sante"] = sprintf($format, "La santé", "de l'adversaire", 0);
    }

    return [$formErrors, $player, $adversaire];
}

function attaque()
{
    // getInfo
    list($player, $adversaire, $combats) = getInfoInSession();

    // Jutilise max pour ne pas descendre la vie en dessous de zero
    $adversaire['sante'] = max(0, $adversaire['sante'] - $player["attaque"]);
    $combats[] = $player["name"] . " attaque " . $adversaire["name"] . " et lui inflige " . $player["attaque"] . " de degats.";

    setInfoInSession($player, $adversaire, $combats);
    // Si l'adversaire n'est pas mort le faire riposter
    if ($adversaire["sante"] <= 0) {
        $combats[] = $player["name"] . " a tué " . $adversaire["name"] . " c'est la fin de la partie";
        setInfoInSession($player, $adversaire, $combats);
        $_SESSION["winner"] = $player["name"];
    } else {
        adversaireAction();
    }
}

function adversaireAction()
{
    list($player, $adversaire, $combats) = getInfoInSession();

    $random = rand(0, 100);
    // 25% de chance de se soigner s'il reste de la mana
    if ($random <= 25 && $adversaire["mana"] > 0) {
        $soin = round(25 / 100 * $adversaire['initial_life']);
        $adversaire['sante'] = min($adversaire["initial_life"], $adversaire['sante'] + $soin);
        $adversaire["mana"] -= 25;
        $combats[] = $adversaire["name"] . " se soigne et restaure " . $soin . " points de vie.";
    } // sinon on attaque
    else {
        $player['sante'] = max(0, $player['sante'] - $adversaire["attaque"]);
        $combats[] = $adversaire["name"] . " attaque " . $player["name"] . " et lui inflige " . $adversaire["attaque"] . " de degats.";

        if ($player["sante"] <= 0) {
            $combats[] = $adversaire["name"] . " a tué " . $player["name"] . " c'est la fin de la partie";
            setInfoInSession($player, $adversaire, $combats);
            $_SESSION["winner"] = $adversaire["name"];
        }
    }

    setInfoInSession($player, $adversaire, $combats);
}

function soin()
{
    list($player, $adversaire, $combats) = getInfoInSession();

    $soin = round(25 / 100 * $player['initial_life']);
    // je ne peux pas me soigner plus que ma vie du départ.
    $player['sante'] = min($player["initial_life"], $player['sante'] + $soin);
    $player["mana"] -= 25;
    $combats[] = $player["name"] . " se soigne et restaure " . $soin . " points de vie.";

    setInfoInSession($player, $adversaire, $combats);
    adversaireAction();
}