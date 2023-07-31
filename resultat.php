<?php
?>
<div id="Resultats">
    <h1>Résultat</h1>
    xxxx est le vainqueur !
    <form class="d-flex justify-content-center" action="" method="post">
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