<?php
require_once __DIR__ . '/vendor/autoload.php';
// require './index.php';
require './classes/player.class.php';
session_start();
$playerOne = $_SESSION['player1'];
$playerTwo = $_SESSION['player2'];
dump($playerOne, $playerTwo);
?>
<div id="Resultats">
    <h1>Résultat</h1>
    <?php
    if (($playerOne->health) > ($playerTwo->health)) {
        echo "$playerOne->name est le vainqueur";
    }
    if (($playerOne->health) < ($playerTwo->health)) {
        echo "$playerTwo->name est le vainqueur";
    }
    if (($playerOne->health) === ($playerTwo->health)) {
        echo "Match nul !!!";
    }


    session_destroy();

    ?>
    <form class="d-flex justify-content-center" action="./index.php" method="post">
        <input name="restart" type="submit" value="Nouveau combat">
    </form>
</div>
</div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let submitRestart = document.querySelector("#restart");
        let alreadyPlaySongRestart = false;
        if (submitRestart) {
            submitRestart.addEventListener("click", function(event) {
                if (alreadyPlaySongRestart)
                    return true;
                event.preventDefault();
                let fatality_song = document.getElementById("fatality-song");
                fatality_song.play();
                alreadyPlaySongRestart = true;
                setTimeout(function() {
                    submitRestart.click();
                }, 2000);
            })
        }
    })
</script>