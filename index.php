<?php
require_once __DIR__ . '/vendor/autoload.php';
include_once "class/Personnage.php";
session_start();

if (isset($_POST["restart"])) {
    removePersonnages();
}

/** @var Personnage $player */
$player = !empty($_SESSION['player']) ? unserialize($_SESSION['player']) : null;
/** @var Personnage $adversaire */
$adversaire = !empty($_SESSION['adversaire']) ? unserialize($_SESSION['adversaire']) : null;


if (!$player || !$adversaire) {
    $playerInfo = $_POST["player"] ?? [];
    $adversaireInfo = $_POST["adversaire"] ?? [];

    if (!empty($playerInfo) && !in_array("", $playerInfo) && !empty($adversaireInfo) && !in_array("", $adversaireInfo)) {
        $player = new Personnage($playerInfo["name"], $playerInfo["attaque"], $playerInfo["mana"], $playerInfo["sante"]);
        $adversaire = new Personnage($adversaireInfo["name"], $adversaireInfo["attaque"], $adversaireInfo["mana"], $adversaireInfo["sante"]);
        savePersonnages($player, $adversaire);
    }
}

function savePersonnages(Personnage $player, Personnage $adversaire)
{
    $_SESSION['player'] = serialize($player);
    $_SESSION['adversaire'] = serialize($adversaire);
}

function removePersonnages()
{
    $_SESSION['player'] = null;
    $_SESSION['adversaire'] = null;
    $_SESSION['combats'] = [];
}


if (isset($_POST["attaque"])) {
    $player->attaque($adversaire);
    savePersonnages($player, $adversaire);
}

$combats = $_SESSION['combats'] ?? [];
?>

<html lang="fr">
<head>
    <title>Battle</title>
    <link rel="stylesheet" href="public/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
            integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V"
            crossorigin="anonymous"></script>
    <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />

</head>

<body>
<div class="container">
    <audio id="audio" src="fight.mp3"></audio>
    <h1 class="animate__animated animate__rubberBand">Battle</h1>
    <?php if ($player == null || $adversaire == null) { ?>
        <div id="prematch">
            <form action="" method="post">
                <div>
                    Joueur <br>
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label">Name</label>
                            <input required type="text" class="form-control" name="player[name]">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Attaque</label>
                            <input required type="number" class="form-control" name="player[attaque]">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Mana</label>
                            <input required type="number" class="form-control" name="player[mana]">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Santé</label>
                            <input required type="number" class="form-control" name="player[sante]">
                        </div>
                    </div>
                </div>
                <hr>
                <div>
                    Adversaire <br>
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label">Name</label>
                            <input required type="text" class="form-control" name="adversaire[name]">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Attaque</label>
                            <input required type="number" class="form-control" name="adversaire[attaque]">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Mana</label>
                            <input required type="number" class="form-control" name="adversaire[mana]">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Santé</label>
                            <input required type="number" class="form-control" name="adversaire[sante]">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="d-flex justify-content-center">
                            <input id="figth" type="submit" value="FIGHT">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    <?php }
    else { ?>
    <div id="match" class="row gx-5">
        <h2>Match</h2>
        <div class="col-6 " id="player">
            <div class="position-relative float-end">
                <img src="https://api.dicebear.com/6.x/lorelei/svg?flip=true&seed=<?php echo $player->name ?>"
                     alt="Avatar"
                     class="avatar">
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <?php echo $player->sante ?>
                </span>
                <ul>
                    <li>Name : <?php echo $player->name ?></li>
                    <li>Attaque : <?php echo $player->attaque ?></li>
                    <li>Mana : <?php echo $player->mana ?></li>
                </ul>
            </div>
        </div>
        <div class="col-6" id="adversaire">
            <div class="position-relative float-start">
                <img src="https://api.dicebear.com/6.x/lorelei/svg?flip=true&seed=<?php echo $adversaire->name ?>"
                     alt="Avatar"
                     class="avatar">
                <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger">
                    <?php echo $adversaire->sante ?>
                </span>
                <ul>
                    <li>Name : <?php echo $adversaire->name ?></li>
                    <li>Attaque : <?php echo $adversaire->attaque ?></li>
                    <li>Mana : <?php echo $adversaire->mana ?></li>
                </ul>
            </div>
        </div>
        <div id="combats">
            <h2>Combat</h2>
            <ul>
                <?php foreach ($combats as $combat) { ?>
                    <li>
                        <i class="fa-solid fa-khanda p-1"></i> <?php echo $combat ?>
                    </li>
                <?php } ?>
            </ul>
            <form action="" method="post">
                <div class="d-flex justify-content-center">
                    <input name="attaque" type="submit" value="Attaquer">
                    <input name="soin" type="submit" value="Se soigner">
                </div>
                <div class="d-flex justify-content-center">
                    <input name="restart" type="submit" value="Stopper le combat">
                </div>
            </form>
        </div>
        <?php if (!$player->is_alive || !$adversaire->is_alive) { ?>
            <div id="Resultats">
                <h1>Résultat</h1>
                <?php if ($player->is_alive) { ?>
                    <?php echo $player->name ?> est le vainqueur !
                <?php } ?>
                <?php if ($adversaire->is_alive) { ?>
                    <?php echo $adversaire->name ?> est le vainqueur !
                <?php } ?>
                <form class="d-flex justify-content-center" action="" method="post">
                    <input name="restart" type="submit" value="Nouveau combat">
                </form>
            </div>
        <?php } ?>
        <?php } ?>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelector("#figth").addEventListener("click", function (event) {
            document.getElementById("figth").classList.add("animate__animated")
            document.getElementById("figth").classList.add("animate__rubberBand")
            setTimeout(function () {
                document.getElementById("figth").classList.remove("animate__rubberBand");
            }, 1000);
            var audio = document.getElementById("audio");
            audio.play();
        })
    });

</script>
</body>
<style>
    .avatar {
        vertical-align: middle;
        width: 100px;
        border-radius: 50%;
    }
</style>
</html>
