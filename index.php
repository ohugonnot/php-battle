<?php
require_once __DIR__ . '/vendor/autoload.php';
require "lib.php";
require "repository.php";
session_start();

// CONTROLLER gestion de la logique
// Gestion de mon formulaire de création de personnage
if ($_SERVER["REQUEST_METHOD"] === 'POST' && isset($_POST["fight"])) {
    list($formErrors, $player, $adversaire) = checkErrorsForm();
    if (empty($formErrors)) {
        if (!empty($player["id"])) {
            $player = getFighter($player["id"]);
        } else {
            $player = addFighter($player);
        }
        if (!empty($adversaire["id"])) {
            $adversaire = getFighter($adversaire["id"]);
        } else {
            $adversaire = addFighter($adversaire);
        }
        $player["initial_life"] = $player["sante"];
        $adversaire["initial_life"] = $adversaire["sante"];
        $fight = addFight($player, $adversaire);
        saveState($player, $adversaire, [], null, $fight);
    }
}

if ($_SERVER["REQUEST_METHOD"] === 'POST' && isset($_POST["attaque"])) {
    attaque();
}

if ($_SERVER["REQUEST_METHOD"] === 'POST' && isset($_POST["soin"])) {
    soin();
}

if ($_SERVER["REQUEST_METHOD"] === 'POST' && isset($_POST["restart"])) {
    removeInfoInSession();
}


// GESTION DE LA VUE
list($player, $adversaire, $combats) = getInfoInSession();
$combatIsBegin = $player && $adversaire;
$winner = $_SESSION["winner"] ?? null;
$fighters = getAllFighters();
?>

<html lang="fr">
<head>
    <title>Battle</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
            integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
<div class="container">
    <audio id="fight-song" src="fight.mp3"></audio>
    <audio id="hadoudken-song" src="Haduken.mp3"></audio>
    <audio id="fatality-song" src="fatality.mp3"></audio>
    <?php require "navbar.php" ?>
    <?php if (!$combatIsBegin) { ?>
        <div id="prematch">
            <form id='formFight' action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                <div class="row">
                    Joueur <br>
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label">Sélectionner un joueur existant</label>
                            <select class="form-select" name="player[id]" id="selectPlayer">
                                <option selected value></option>
                                <?php foreach ($fighters ?? [] as $fighter) { ?>
                                    <option value="<?= $fighter["id"] ?>"><?= $fighter["name"] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div id="formPlayer">
                        <div class="col-12 mt-3">ou créer votre propre joueur</div>
                        <div class="errors">
                            <ul>
                                <?php foreach ($formErrors["player"] ?? [] as $error) { ?>
                                    <li class="text-danger"><?php echo $error ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label">Name</label>
                                <input required type="text" class="form-control" name="player[name]"
                                       value="<?php echo $_POST["player"]["name"] ?? "" ?>">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Attaque</label>
                                <input required type="number"
                                       class="form-control <?php echo isset($formErrors["player"]["attaque"]) ? "is-invalid" : "" ?>"
                                       name="player[attaque]"
                                       value="<?php echo $_POST["player"]["attaque"] ?? "100" ?>">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Mana</label>
                                <input required type="number"
                                       class="form-control <?php echo isset($formErrors["player"]["mana"]) ? "is-invalid" : "" ?>"
                                       name="player[mana]"
                                       value="<?php echo $_POST["player"]["mana"] ?? "100" ?>">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Santé</label>
                                <input required type="number"
                                       class="form-control <?php echo isset($formErrors["player"]["sante"]) ? "is-invalid" : "" ?>"
                                       name="player[sante]"
                                       value="<?php echo $_POST["player"]["sante"] ?? "1000" ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    Adversaire <br>
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label">Sélectionner un adversaire existant</label>
                            <select class="form-select" name="adversaire[id]" id="selectAdversaire">
                                <option selected value></option>
                                <?php foreach ($fighters ?? [] as $fighter) { ?>
                                    <option value="<?= $fighter["id"] ?>"><?= $fighter["name"] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div id="formAdversaire">
                        <div class="col-12 mt-3">ou créer votre propre adversaire</div>
                        <div class="errors">
                            <ul>
                                <?php foreach ($formErrors["adversaire"] ?? [] as $error) { ?>
                                    <li class="text-danger"><?php echo $error ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label">Name</label>
                                <input required type="text" class="form-control" name="adversaire[name]"
                                       value="<?php echo $_POST["adversaire"]["name"] ?? "" ?>">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Attaque</label>
                                <input required type="number"
                                       class="form-control <?php echo isset($formErrors["adversaire"]["attaque"]) ? "is-invalid" : "" ?>"
                                       name="adversaire[attaque]"
                                       value="<?php echo $_POST["adversaire"]["attaque"] ?? "100" ?>">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Mana</label>
                                <input required type="number"
                                       class="form-control <?php echo isset($formErrors["adversaire"]["mana"]) ? "is-invalid" : "" ?>"
                                       name="adversaire[mana]"
                                       value="<?php echo $_POST["adversaire"]["mana"] ?? "100" ?>">
                            </div>
                            <div class=" col-6">
                                <label class="form-label">Santé</label>
                                <input required type="number"
                                       class="form-control <?php echo isset($formErrors["adversaire"]["sante"]) ? "is-invalid" : "" ?>"
                                       name="adversaire[sante]"
                                       value="<?php echo $_POST["adversaire"]["sante"] ?? "1000" ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="d-flex justify-content-center">
                        <input class="btn btn-outline-primary" name="fight" type="submit" value="FIGHT">
                    </div>
                </div>
            </form>
        </div>
    <?php } else { ?>
        <div id="match" class="row gx-5">
            <h2>Match</h2>
            <div class="col-6 ">
                <div class="position-relative float-end">
                    <img id="player"
                         src="https://api.dicebear.com/6.x/lorelei/svg?flip=false&seed=<?php echo $player["name"] ?>"
                         alt="Avatar"
                         class="avatar float-end">
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?php echo $player["sante"] ?>
                </span>
                    <ul>
                        <li>Name : <?php echo $player["name"] ?></li>
                        <li>Attaque : <?php echo $player["attaque"] ?></li>
                        <li>Mana : <?php echo $player["mana"] ?></li>
                    </ul>
                </div>
            </div>
            <div class="col-6">
                <div class="position-relative float-start">
                    <img id="adversaire"
                         src="https://api.dicebear.com/6.x/lorelei/svg?flip=true&seed=<?php echo $adversaire["name"] ?>"
                         alt="Avatar"
                         class="avatar">
                    <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger">
                    <?php echo $adversaire["sante"] ?>
                </span>
                    <ul>
                        <li>Name : <?php echo $adversaire["name"] ?></li>
                        <li>Attaque : <?php echo $adversaire["attaque"] ?></li>
                        <li>Mana : <?php echo $adversaire["mana"] ?></li>
                    </ul>
                </div>
            </div>
            <div id="combats">
                <h2>Combat</h2>
                <ul style="max-height: 300px; overflow: auto">
                    <?php foreach (array_reverse($combats) ?? [] as $combat) { ?>
                        <li>
                            <i class="fa-solid fa-khanda p-1"></i><?php echo $combat ?>
                        </li>
                    <?php } ?>
                </ul>
                <?php if (!$winner) { ?>
                    <form id='actionForm' action="index.php" method="post">
                        <div class="d-flex justify-content-center">
                            <input class="btn btn-outline-danger" name="attaque" type="submit" value="Attaquer">
                            <input class="btn btn-outline-success" name="soin" type="submit" value="Se soigner">
                        </div>
                        <div class="d-flex justify-content-center">
                            <input class="btn btn-outline-warning" name="restart" type="submit"
                                   value="Stopper le combat">
                        </div>
                    </form>
                <?php } ?>
            </div>
            <?php if ($winner) { ?>
                <div id="Resultats">
                    <h1>Résultat</h1>
                    <?php echo $winner ?> est le vainqueur !
                    <form class="d-flex justify-content-center" action="" method="post">
                        <input class="btn btn-outline-warning" name="restart" type="submit" value="Nouveau combat">
                    </form>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Gerer mes selecteurs et mes required sur les forms
        let $selectPlayer = $("#selectPlayer");
        let $formPlayer = $("#formPlayer");
        let $selectAdversaire = $("#selectAdversaire");
        let $formAdversaire = $("#formAdversaire");

        $selectPlayer.on("change", function () {
            $selectAdversaire.find('option').prop("disabled", false)
            if ($selectPlayer.val() !== '') {
                $formPlayer.hide();
                $formPlayer.find("input").prop('required', false)
                $selectAdversaire.find('option[value="' + $selectPlayer.val() + '"]').prop("disabled", true);
            } else {
                $formPlayer.show();
                $formPlayer.find("input").prop('required', true)
            }
        })

        $selectAdversaire.on("change", function () {
            $selectPlayer.find('option').prop("disabled", false)
            if ($selectAdversaire.val() !== '') {
                $formAdversaire.hide();
                $formAdversaire.find("input").prop('required', false)
                $selectPlayer.find('option[value="' + $selectAdversaire.val() + '"]').prop("disabled", true);
            } else {
                $formAdversaire.show();
                $formAdversaire.find("input").prop('required', true)
            }
        })


        // Jouer les sons sur les différents boutons
        let submitFight = document.querySelector("[name=fight]");
        let alreadyPlaySongFight = false;
        if (submitFight) {
            submitFight.addEventListener("click", function (event) {
                if (alreadyPlaySongFight)
                    return true;
                event.preventDefault();
                submitFight.classList.add("animate__animated");
                submitFight.classList.add("animate__rubberBand");
                setTimeout(function () {
                    submitFight.classList.remove("animate__rubberBand");
                }, 1000);
                let fight_song = document.getElementById("fight-song");
                fight_song.play();
                alreadyPlaySongFight = true;
                setTimeout(function () {
                    submitFight.click();
                }, 500);
            })
        }

        let submitAttaque = document.querySelector("[name=attaque]");
        let alreadyPlaySong = false;
        if (submitAttaque) {
            submitAttaque.addEventListener("click", function (event) {
                if (alreadyPlaySong)
                    return true;
                event.preventDefault();
                let player = document.querySelector("#player")
                let adversaire = document.querySelector("#adversaire")
                player.classList.add("animate__animated");
                player.classList.add("animate__rubberBand");
                adversaire.classList.add("bc-red");
                submitAttaque.classList.add("animate__animated");
                submitAttaque.classList.add("animate__rubberBand");
                setTimeout(function () {
                    submitAttaque.classList.remove("animate__rubberBand");
                    player.classList.remove("animate__rubberBand");
                    adversaire.classList.remove("bc-red");
                }, 1000);
                let hadouken_song = document.getElementById("hadoudken-song");
                hadouken_song.play();
                alreadyPlaySong = true;
                setTimeout(function () {
                    submitAttaque.click();
                }, 1000);
            })
        }

        let submitRestart = document.querySelector("[name=restart]");
        let alreadyPlaySongRestart = false;
        if (submitRestart) {
            submitRestart.addEventListener("click", function (event) {
                if (alreadyPlaySongRestart)
                    return true;
                event.preventDefault();
                let fatality_song = document.getElementById("fatality-song");
                fatality_song.play();
                alreadyPlaySongRestart = true;
                setTimeout(function () {
                    submitRestart.click();
                }, 2000);
            })
        }
    });
</script>
<style>
    .avatar {
        vertical-align: middle;
        width: 100px;
        border-radius: 50%;
    }

    .bc-red {
        background-color: red;
    }
</style>
</body>
</html>
