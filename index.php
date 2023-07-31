<?php
require_once __DIR__ . '/vendor/autoload.php';
require './classes/player.class.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // $playerOne->attack($playerTwo);
    $playerOne = new Player($_SESSION['player_name'], $_SESSION['player_attaque'], $_SESSION['player_mana'], $_SESSION['player_sante']);
    $playerTwo = new Player($_SESSION['adversaire_name'], $_SESSION['adversaire_attaque'], $_SESSION['adversaire_mana'], $_SESSION['adversaire_sante']);
    $_SESSION['player1'] = $playerOne;
    $_SESSION['player2'] = $playerTwo;

    dump($playerOne, $playerTwo);
    dump($_SESSION);
}

// $playerOneName = "Superman";
// $playerOnePower = 100;
// $playerOneMana = 100;
// $playerOneHealth = 100;
// $playerOne = new Player($playerOneName, $playerOnePower, $playerOneMana, $playerOneHealth);
// dump($playerOne);

// $playerTwoName = "Batman";
// $playerTwoPower = 100;
// $playerTwoMana = 100;
// $playerTwoHealth = 100;
// $playerTwo = new Player($playerTwoName, $playerTwoPower, $playerTwoMana, $playerTwoHealth);
// $playerOne->attack($playerTwo);
// dump($playerOne, $playerTwo);


?>


<html lang="fr">

<head>
    <title>Battle</title>
    <link rel="stylesheet" href="public/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

</head>

<body>
    <div class="container">
        <audio id="fight-song" src="fight.mp3"></audio>
        <audio id="hadoudken-song" src="Haduken.mp3"></audio>
        <audio id="fatality-song" src="fatality.mp3"></audio>
        <h1 class="animate__animated animate__rubberBand">Battle</h1>
        <div id="prematch">
            <form id='formFight' action="attack.php" method="post">
                <div>
                    Joueur <br>
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label">Name</label>
                            <input required type="text" class="form-control" name="player-name">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Attaque</label>
                            <input required type="number" class="form-control" value="100" name="player-attaque">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Mana</label>
                            <input required type="number" class="form-control" value="100" name="player-mana">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Santé</label>
                            <input required type="number" class="form-control" value="100" name="player-sante">
                        </div>
                    </div>
                </div>
                <hr>
                <div>
                    Adversaire <br>
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label">Name</label>
                            <input required type="text" class="form-control" name="adversaire-name">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Attaque</label>
                            <input required type="number" class="form-control" value="100" name="adversaire-attaque">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Mana</label>
                            <input required type="number" class="form-control" value="100" name="adversaire-mana">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Santé</label>
                            <input required type="number" class="form-control" value="100" name="adversaire-sante">
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="d-flex justify-content-center">
                        <input id="fight" type="submit" value="FIGHT">
                    </div>
                </div>
            </form>
        </div>


        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let submitFight = document.querySelector("#fight");
                if (submitFight) {
                    submitFight.addEventListener("click", function(event) {
                        event.preventDefault();
                        submitFight.classList.add("animate__animated");
                        submitFight.classList.add("animate__rubberBand");
                        setTimeout(function() {
                            submitFight.classList.remove("animate__rubberBand");
                        }, 1000);
                        let fight_song = document.getElementById("fight-song");
                        fight_song.play();
                        setTimeout(function() {
                            document.forms["formFight"].submit();
                        }, 500);
                    })
                }

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
        </script>
</body>


</html>