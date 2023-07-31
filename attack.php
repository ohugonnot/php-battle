<?php
require_once __DIR__ . '/vendor/autoload.php';
// require './index.php';
require './classes/player.class.php';
session_start();
if (($_SERVER['REQUEST_METHOD'] == 'POST') && !isset($_POST['attaque'])) {

    $playerOne = new Player($_POST['player-name'], $_POST['player-attaque'], $_POST['player-mana'], $_POST['player-sante']);
    $playerTwo = new Player($_POST['adversaire-name'], $_POST['adversaire-attaque'], $_POST['adversaire-mana'], $_POST['adversaire-sante']);
    $_SESSION['player1'] = $playerOne;
    $_SESSION['player2'] = $playerTwo;
}
dump($_SESSION);
// $_SESSION['fight'] = true;
if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['attaque'])) {
    $playerOne = $_SESSION['player1'];
    $playerTwo = $_SESSION['player2'];
    // while ($playerOne->getLifeStatus() && $playerTwo->getLifeStatus())
    $playerOne->attack($playerTwo);
    $_SESSION['player2'] = $playerTwo;
    $playerTwo->getLifeStatus();
    $playerTwo->attack($playerOne);
    $_SESSION['player1'] = $playerOne;
    $playerOne->getLifeStatus();

    dump($playerOne, $playerTwo);
}
if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['soin'])) {
    $playerOne->cure();
    dump($playerOne, $playerTwo);
}
?>

<div id="match" class="row gx-5">
    <h2>Match</h2>
    <div class="col-6 ">
        <div class="position-relative float-end">
            <img id="player" src="https://api.dicebear.com/6.x/lorelei/svg?flip=false&seed=test" alt="Avatar" class="avatar float-end">
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">

            </span>
            <ul>
                <li>Name : <?= $playerOne->name ?></li>
                <li>Attaque : <?= $playerOne->power ?></li>
                <li>Mana : <?= $playerOne->mana ?></li>
                <li>Vie : <?= $playerOne->health ?></li>
            </ul>
        </div>
    </div>
    <div class="col-6" id="adversaire">
        <div class="position-relative float-start">
            <img src="https://api.dicebear.com/6.x/lorelei/svg?flip=true&seed=test2" alt="Avatar" class="avatar">
            <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger">

            </span>
            <ul>
                <li>Name : <?= $playerTwo->name ?></li>
                <li>Attaque : <?= $playerTwo->power ?></li>
                <li>Mana : <?= $playerTwo->mana ?></li>
                <li>Vie : <?= $playerTwo->health ?></li>
            </ul>
        </div>
    </div>
    <div id="combats">
        <h2>Combat</h2>
        <ul>

            <li>
                <i class="fa-solid fa-khanda p-1"></i> test
            </li>

        </ul>
        <form id='actionForm' action="attack.php" method="post">
            <div class="d-flex justify-content-center">
                <input id="attaque" name="attaque" type="submit" value="Attaquer">
                <input name="soin" type="submit" value="Se soigner">
            </div>
            <div class="d-flex justify-content-center">
                <input id="restart" name="restart" type="submit" value="Stopper le combat">
            </div>
        </form>
    </div>
    <style>
        .avatar {
            vertical-align: middle;
            width: 100px;
            border-radius: 50%;
        }
    </style>
    <!-- <script>
        document.addEventListener("DOMContentLoaded", function() {


            let submitAttaque = document.querySelector("#attaque");
            let alreadyPlaySong = false;
            if (submitAttaque) {
                submitAttaque.addEventListener("click", function(event) {
                    if (alreadyPlaySong)
                        return true;
                    event.preventDefault();
                    let player = document.querySelector("#player")
                    player.classList.add("animate__animated");
                    player.classList.add("animate__rubberBand");
                    submitAttaque.classList.add("animate__animated");
                    submitAttaque.classList.add("animate__rubberBand");
                    setTimeout(function() {
                        submitAttaque.classList.remove("animate__rubberBand");
                        player.classList.remove("animate__rubberBand");
                    }, 1000);
                    let hadouken_song = document.getElementById("hadoudken-song");
                    hadouken_song.play();
                    alreadyPlaySong = true;
                    setTimeout(function() {
                        submitAttaque.click();
                    }, 1000);
                })
            }


        });
    </script> -->