<?php

function connectBDD(bool $is_fluent = false): PDO|Envms\FluentPDO\Query
{
    global $db;
    global $fluent;

    if (!$is_fluent && !empty($db))
        return $db;

    if ($is_fluent && !empty($fluent))
        return $fluent;

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "battle";

    try {
        $db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        if ($is_fluent) {
            $fluent = new \Envms\FluentPDO\Query($db);
            return $fluent;
        }

        return $db;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        exit;
    }
}

function getAllFighters(): array
{
    $db = connectBDD(true);
    return $db->from("fighters")->fetchAll();
}

function getAllFights(): array
{
    $db = connectBDD(true);
    return $db->from("fights")->fetchAll();
}

function getFighter(string $id): array|bool
{
    $db = connectBDD(true);
    return $db->from("fighters")->where("id", $id)->fetch();
}

function getFighterByName(string $name): array|bool
{
    $db = connectBDD(true);
    return $db->from("fighters")->where("name", $name)->fetch();
}

function addFighter(array $fighter): array
{
    $db = connectBDD(true);
    $fighter_id = $db->insertInto('fighters')->values($fighter)->execute();
    return getFighter($fighter_id);
}

function getFight(string $id)
{
    $db = connectBDD(true);
    return $db->from("fights")->where("id", $id)->fetch();
}

function addFight(array $player, array $adversaire): array
{
    $db = connectBDD(true);
    $fight = ["fighter1" => $player["id"], "fighter2" => $adversaire["id"]];
    $fight_id = $db->insertInto('fights')->values($fight)->execute();
    return getFight($fight_id);
}

function updateLog(array $log): void
{
    $db = connectBDD(true);
    $log = ["logs" => json_encode($log)];
    $db->update('fights')->set($log)->where("id", $_SESSION["fight"]["id"])->execute();
}

function updateWinner(array $winner): void
{
    $db = connectBDD(true);
    $log = ["winner" => $winner["id"]];
    $db->update('fights')->set($log)->where("id", $_SESSION["fight"]["id"])->execute();
}