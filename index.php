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
        $player["initial_life"]     = $player["sante"];
        $adversaire["initial_life"] = $adversaire["sante"];
        $player["manaMax"]          = $player["mana"];
        $adversaire["manaMax"]      = $adversaire["mana"];
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

if ($_SERVER["REQUEST_METHOD"] === 'POST' && isset($_POST["special"])) {
    coupSpecial();
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
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Battle of Shadows</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Crimson+Text:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/battle.css">
</head>
<body>

<audio id="fight-song" src="fight.mp3"></audio>
<audio id="hadoudken-song" src="Haduken.mp3"></audio>
<audio id="fatality-song" src="fatality.mp3"></audio>

<?php require "navbar.php" ?>

<?php if (!$combatIsBegin): ?>
<!-- ============================================================
     SCREEN 1 — SETUP
     ============================================================ -->
<section id="screen-setup" class="screen">

    <div class="setup-header">
        <h1>⚔ Battle of Shadows ⚔</h1>
        <p class="subtitle">Que le sang coule et les légendes naissent</p>
    </div>

    <form id="formFight" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
    <div class="setup-arena">

        <!-- Player -->
        <div class="fighter-card player-card">
            <div class="card-title">⚔ Champion</div>

            <div class="silhouette-wrap">
                <svg class="silhouette silhouette-warrior" width="90" height="140" viewBox="0 0 90 140" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <ellipse cx="45" cy="18" rx="12" ry="14" fill="#c9a84c" opacity="0.85"/>
                    <polygon points="35,10 45,2 55,10 50,12 45,6 40,12" fill="#f0d080" opacity="0.9"/>
                    <rect x="30" y="32" width="30" height="36" rx="3" fill="#8a7040" opacity="0.9"/>
                    <ellipse cx="45" cy="44" rx="12" ry="10" fill="#c9a84c" opacity="0.6"/>
                    <rect x="28" y="64" width="34" height="6" rx="2" fill="#5a3a10" opacity="0.95"/>
                    <rect x="16" y="33" width="13" height="32" rx="5" fill="#8a7040" opacity="0.85"/>
                    <rect x="61" y="33" width="13" height="32" rx="5" fill="#8a7040" opacity="0.85"/>
                    <rect x="74" y="18" width="5" height="55" rx="2" fill="#d0d8e0" opacity="0.9"/>
                    <polygon points="74,18 79,18 76.5,8" fill="#e0e8f0" opacity="0.95"/>
                    <rect x="70" y="42" width="13" height="4" rx="2" fill="#c9a84c" opacity="0.9"/>
                    <ellipse cx="10" cy="52" rx="9" ry="12" fill="#5a3a10" opacity="0.9"/>
                    <ellipse cx="10" cy="52" rx="6" ry="9" fill="#8b1a1a" opacity="0.7"/>
                    <line x1="10" y1="43" x2="10" y2="61" stroke="#c9a84c" stroke-width="1.5" opacity="0.8"/>
                    <line x1="4" y1="52" x2="16" y2="52" stroke="#c9a84c" stroke-width="1.5" opacity="0.8"/>
                    <rect x="30" y="70" width="13" height="44" rx="5" fill="#5a4820" opacity="0.9"/>
                    <rect x="47" y="70" width="13" height="44" rx="5" fill="#5a4820" opacity="0.9"/>
                    <rect x="28" y="106" width="17" height="12" rx="3" fill="#2a1e0e" opacity="0.95"/>
                    <rect x="45" y="106" width="17" height="12" rx="3" fill="#2a1e0e" opacity="0.95"/>
                    <ellipse cx="36" cy="90" rx="7" ry="5" fill="#c9a84c" opacity="0.5"/>
                    <ellipse cx="53" cy="90" rx="7" ry="5" fill="#c9a84c" opacity="0.5"/>
                </svg>
            </div>

            <div class="form-group">
                <label>Guerrier existant</label>
                <select class="form-select" name="player[id]" id="selectPlayer">
                    <option value="">— Créer un nouveau champion —</option>
                    <?php foreach ($fighters ?? [] as $fighter): ?>
                        <option value="<?= htmlspecialchars($fighter["id"]) ?>"><?= htmlspecialchars($fighter["name"]) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="formPlayer">
                <?php if (!empty($formErrors["player"])): ?>
                    <div class="form-errors">
                        <ul>
                            <?php foreach ($formErrors["player"] as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label>Nom du héros</label>
                    <input type="text" name="player[name]" placeholder="Nom du héros"
                           value="<?= htmlspecialchars($_POST["player"]["name"] ?? "") ?>">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Points de vie</label>
                        <input type="number" name="player[sante]" min="1"
                               value="<?= htmlspecialchars($_POST["player"]["sante"] ?? "1000") ?>">
                    </div>
                    <div class="form-group">
                        <label>Attaque</label>
                        <input type="number" name="player[attaque]" min="1"
                               value="<?= htmlspecialchars($_POST["player"]["attaque"] ?? "100") ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label>Mana</label>
                    <input type="number" name="player[mana]" min="0"
                           value="<?= htmlspecialchars($_POST["player"]["mana"] ?? "100") ?>">
                </div>
            </div>
        </div>

        <!-- VS -->
        <div class="vs-center">
            <div class="vs-flames">🔥</div>
            <div class="vs-text">VS</div>
            <div class="vs-flames">🔥</div>
        </div>

        <!-- Enemy -->
        <div class="fighter-card enemy-card">
            <div class="card-title">☠ Ennemi</div>

            <div class="silhouette-wrap">
                <svg class="silhouette silhouette-enemy" width="90" height="140" viewBox="0 0 90 140" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <ellipse cx="45" cy="18" rx="13" ry="15" fill="#5a1010" opacity="0.9"/>
                    <ellipse cx="45" cy="18" rx="10" ry="12" fill="#8b2020" opacity="0.6"/>
                    <path d="M34 10 Q28 2 32 -2 Q36 4 38 10" fill="#3a0a0a" opacity="0.9"/>
                    <path d="M56 10 Q62 2 58 -2 Q54 4 52 10" fill="#3a0a0a" opacity="0.9"/>
                    <ellipse cx="40" cy="17" rx="4" ry="3" fill="#ff2020" opacity="0.7"/>
                    <ellipse cx="50" cy="17" rx="4" ry="3" fill="#ff2020" opacity="0.7"/>
                    <path d="M22 35 Q30 30 45 32 Q60 30 68 35 L72 110 Q60 115 45 114 Q30 115 18 110 Z" fill="#1a0808" opacity="0.95"/>
                    <path d="M28 35 Q36 40 45 38 Q54 40 62 35" stroke="#5a1010" stroke-width="1.5" fill="none" opacity="0.8"/>
                    <polygon points="22,35 16,25 20,38" fill="#3a0a0a" opacity="0.9"/>
                    <polygon points="68,35 74,25 70,38" fill="#3a0a0a" opacity="0.9"/>
                    <rect x="66" y="15" width="5" height="60" rx="2" fill="#2a0a0a" opacity="0.95"/>
                    <path d="M66 15 Q80 20 78 35 Q70 30 66 35" fill="#1a0505" opacity="0.9"/>
                    <rect x="62" y="40" width="14" height="4" rx="2" fill="#5a1010" opacity="0.9"/>
                    <path d="M28 100 L24 130 Q30 132 36 130 L38 100" fill="#120606" opacity="0.95"/>
                    <path d="M52 100 L54 130 Q60 132 66 130 L62 100" fill="#120606" opacity="0.95"/>
                    <rect x="12" y="38" width="13" height="30" rx="5" fill="#1a0808" opacity="0.9"/>
                    <rect x="65" y="38" width="13" height="30" rx="5" fill="#1a0808" opacity="0.9"/>
                    <text x="45" y="75" font-size="18" text-anchor="middle" fill="#ff4040" opacity="0.4" font-family="serif">ᚱ</text>
                </svg>
            </div>

            <div class="form-group">
                <label>Guerrier existant</label>
                <select class="form-select" name="adversaire[id]" id="selectAdversaire">
                    <option value="">— Invoquer un ennemi —</option>
                    <?php foreach ($fighters ?? [] as $fighter): ?>
                        <option value="<?= htmlspecialchars($fighter["id"]) ?>"><?= htmlspecialchars($fighter["name"]) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="formAdversaire">
                <?php if (!empty($formErrors["adversaire"])): ?>
                    <div class="form-errors">
                        <ul>
                            <?php foreach ($formErrors["adversaire"] as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label>Nom de l'ennemi</label>
                    <input type="text" name="adversaire[name]" placeholder="Nom de l'ennemi"
                           value="<?= htmlspecialchars($_POST["adversaire"]["name"] ?? "") ?>">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Points de vie</label>
                        <input type="number" name="adversaire[sante]" min="1"
                               value="<?= htmlspecialchars($_POST["adversaire"]["sante"] ?? "1000") ?>">
                    </div>
                    <div class="form-group">
                        <label>Attaque</label>
                        <input type="number" name="adversaire[attaque]" min="1"
                               value="<?= htmlspecialchars($_POST["adversaire"]["attaque"] ?? "100") ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label>Mana</label>
                    <input type="number" name="adversaire[mana]" min="0"
                           value="<?= htmlspecialchars($_POST["adversaire"]["mana"] ?? "100") ?>">
                </div>
            </div>
        </div>

    </div><!-- .setup-arena -->

    <div class="fight-btn-wrap">
        <input class="btn-fight" name="fight" type="submit" value="⚔  COMBATTRE  ⚔">
    </div>

    </form>

</section>

<?php elseif ($winner): ?>
<!-- ============================================================
     SCREEN 3 — VICTORY / DEFEAT
     ============================================================ -->
<section id="screen-victory" class="screen">

    <?php if ($winner === $player["name"]): ?>
        <div class="result-title victory-text">VICTOIRE !</div>
    <?php else: ?>
        <div class="result-title defeat-text">DÉFAITE...</div>
    <?php endif; ?>

    <div class="rune-divider"><span>✦ ᚠ ᚢ ᚦ ✦</span></div>

    <div class="result-winner"><?= htmlspecialchars($winner) ?> triomphe</div>

    <div class="result-stats">
        <div class="stat-item">
            <span class="stat-value"><?= count($combats) ?></span>
            <span class="stat-label">Tours de combat</span>
        </div>
        <div class="stat-item">
            <span class="stat-value"><?= $winner === $player["name"] ? $player["sante"] : $adversaire["sante"] ?></span>
            <span class="stat-label">PV restants</span>
        </div>
    </div>

    <form action="" method="post">
        <input class="btn-replay" name="restart" type="submit" value="↺  Rejouer">
    </form>

</section>

<?php else: ?>
<!-- ============================================================
     SCREEN 2 — BATTLE
     ============================================================ -->
<section id="screen-battle" class="screen">

    <div class="battle-header">
        Chroniques du Combat — Tour <span><?= count($combats) + 1 ?></span>
    </div>

    <div class="combatants">

        <!-- Player -->
        <div class="combatant player-side">
            <div class="combatant-name"><?= htmlspecialchars($player["name"]) ?></div>
            <div class="combatant-silhouette" id="player">
                <svg width="80" height="120" viewBox="0 0 90 140" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <ellipse cx="45" cy="18" rx="12" ry="14" fill="#c9a84c" opacity="0.85"/>
                    <polygon points="35,10 45,2 55,10 50,12 45,6 40,12" fill="#f0d080" opacity="0.9"/>
                    <rect x="30" y="32" width="30" height="36" rx="3" fill="#8a7040" opacity="0.9"/>
                    <ellipse cx="45" cy="44" rx="12" ry="10" fill="#c9a84c" opacity="0.6"/>
                    <rect x="28" y="64" width="34" height="6" rx="2" fill="#5a3a10" opacity="0.95"/>
                    <rect x="16" y="33" width="13" height="32" rx="5" fill="#8a7040" opacity="0.85"/>
                    <rect x="61" y="33" width="13" height="32" rx="5" fill="#8a7040" opacity="0.85"/>
                    <rect x="74" y="18" width="5" height="55" rx="2" fill="#d0d8e0" opacity="0.9"/>
                    <polygon points="74,18 79,18 76.5,8" fill="#e0e8f0" opacity="0.95"/>
                    <rect x="70" y="42" width="13" height="4" rx="2" fill="#c9a84c" opacity="0.9"/>
                    <ellipse cx="10" cy="52" rx="9" ry="12" fill="#5a3a10" opacity="0.9"/>
                    <ellipse cx="10" cy="52" rx="6" ry="9" fill="#8b1a1a" opacity="0.7"/>
                    <line x1="10" y1="43" x2="10" y2="61" stroke="#c9a84c" stroke-width="1.5" opacity="0.8"/>
                    <line x1="4" y1="52" x2="16" y2="52" stroke="#c9a84c" stroke-width="1.5" opacity="0.8"/>
                    <rect x="30" y="70" width="13" height="44" rx="5" fill="#5a4820" opacity="0.9"/>
                    <rect x="47" y="70" width="13" height="44" rx="5" fill="#5a4820" opacity="0.9"/>
                    <rect x="28" y="106" width="17" height="12" rx="3" fill="#2a1e0e" opacity="0.95"/>
                    <rect x="45" y="106" width="17" height="12" rx="3" fill="#2a1e0e" opacity="0.95"/>
                    <ellipse cx="36" cy="90" rx="7" ry="5" fill="#c9a84c" opacity="0.5"/>
                    <ellipse cx="53" cy="90" rx="7" ry="5" fill="#c9a84c" opacity="0.5"/>
                </svg>
            </div>
            <div class="bars">
                <?php
                $pHpPct      = $player["initial_life"] > 0 ? max(0, round($player["sante"] / $player["initial_life"] * 100)) : 0;
                $playerManaMax = $player["manaMax"] ?? $player["mana"];
                $pManaPct    = $playerManaMax > 0 ? max(0, round($player["mana"] / $playerManaMax * 100)) : 0;
                $pLowHp      = $pHpPct <= 25 ? ' low' : '';
                ?>
                <div class="bar-wrap">
                    <div class="bar-label">
                        <span>Vie</span>
                        <span class="bar-val"><?= $player["sante"] ?> / <?= $player["initial_life"] ?></span>
                    </div>
                    <div class="bar-bg">
                        <div class="bar-fill hp-fill<?= $pLowHp ?>" style="width:<?= $pHpPct ?>%"></div>
                    </div>
                </div>
                <div class="bar-wrap">
                    <div class="bar-label">
                        <span>Mana</span>
                        <span class="bar-val"><?= $player["mana"] ?> / <?= $playerManaMax ?></span>
                    </div>
                    <div class="bar-bg">
                        <div class="bar-fill mana-fill" style="width:<?= $pManaPct ?>%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Adversaire -->
        <div class="combatant enemy-side">
            <div class="combatant-name"><?= htmlspecialchars($adversaire["name"]) ?></div>
            <div class="combatant-silhouette" id="adversaire">
                <svg width="80" height="120" viewBox="0 0 90 140" fill="none" xmlns="http://www.w3.org/2000/svg" style="transform:scaleX(-1)">
                    <ellipse cx="45" cy="18" rx="13" ry="15" fill="#5a1010" opacity="0.9"/>
                    <ellipse cx="45" cy="18" rx="10" ry="12" fill="#8b2020" opacity="0.6"/>
                    <path d="M34 10 Q28 2 32 -2 Q36 4 38 10" fill="#3a0a0a" opacity="0.9"/>
                    <path d="M56 10 Q62 2 58 -2 Q54 4 52 10" fill="#3a0a0a" opacity="0.9"/>
                    <ellipse cx="40" cy="17" rx="4" ry="3" fill="#ff2020" opacity="0.7"/>
                    <ellipse cx="50" cy="17" rx="4" ry="3" fill="#ff2020" opacity="0.7"/>
                    <path d="M22 35 Q30 30 45 32 Q60 30 68 35 L72 110 Q60 115 45 114 Q30 115 18 110 Z" fill="#1a0808" opacity="0.95"/>
                    <path d="M28 35 Q36 40 45 38 Q54 40 62 35" stroke="#5a1010" stroke-width="1.5" fill="none" opacity="0.8"/>
                    <polygon points="22,35 16,25 20,38" fill="#3a0a0a" opacity="0.9"/>
                    <polygon points="68,35 74,25 70,38" fill="#3a0a0a" opacity="0.9"/>
                    <rect x="66" y="15" width="5" height="60" rx="2" fill="#2a0a0a" opacity="0.95"/>
                    <path d="M66 15 Q80 20 78 35 Q70 30 66 35" fill="#1a0505" opacity="0.9"/>
                    <rect x="62" y="40" width="14" height="4" rx="2" fill="#5a1010" opacity="0.9"/>
                    <path d="M28 100 L24 130 Q30 132 36 130 L38 100" fill="#120606" opacity="0.95"/>
                    <path d="M52 100 L54 130 Q60 132 66 130 L62 100" fill="#120606" opacity="0.95"/>
                    <rect x="12" y="38" width="13" height="30" rx="5" fill="#1a0808" opacity="0.9"/>
                    <rect x="65" y="38" width="13" height="30" rx="5" fill="#1a0808" opacity="0.9"/>
                    <text x="45" y="75" font-size="18" text-anchor="middle" fill="#ff4040" opacity="0.4" font-family="serif">ᚱ</text>
                </svg>
            </div>
            <div class="bars">
                <?php
                $eHpPct        = $adversaire["initial_life"] > 0 ? max(0, round($adversaire["sante"] / $adversaire["initial_life"] * 100)) : 0;
                $advManaMax    = $adversaire["manaMax"] ?? $adversaire["mana"];
                $eManaPct      = $advManaMax > 0 ? max(0, round($adversaire["mana"] / $advManaMax * 100)) : 0;
                $eLowHp        = $eHpPct <= 25 ? ' low' : '';
                ?>
                <div class="bar-wrap">
                    <div class="bar-label">
                        <span>Vie</span>
                        <span class="bar-val"><?= $adversaire["sante"] ?> / <?= $adversaire["initial_life"] ?></span>
                    </div>
                    <div class="bar-bg">
                        <div class="bar-fill hp-fill<?= $eLowHp ?>" style="width:<?= $eHpPct ?>%"></div>
                    </div>
                </div>
                <div class="bar-wrap">
                    <div class="bar-label">
                        <span>Mana</span>
                        <span class="bar-val"><?= $adversaire["mana"] ?> / <?= $advManaMax ?></span>
                    </div>
                    <div class="bar-bg">
                        <div class="bar-fill mana-fill" style="width:<?= $eManaPct ?>%"></div>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- .combatants -->

    <div class="turn-indicator">Votre tour — choisissez une action</div>

    <form id="actionForm" action="index.php" method="post">
        <div class="actions">
            <input class="btn-action btn-attack" name="attaque" type="submit" value="⚔ Attaquer">
            <input class="btn-action btn-heal" name="soin" type="submit"
                   value="✨ Se soigner (25 mana)"
                   <?= $player["mana"] < 25 ? 'disabled' : '' ?>>
            <?php if ($player["mana"] >= 50): ?>
            <input class="btn-action btn-special" name="special" type="submit" value="⚡ Coup Spécial">
            <?php endif; ?>
            <input class="btn-action btn-stop" name="restart" type="submit" value="✦ Stopper le combat">
        </div>
    </form>

    <div class="log-section">
        <div class="log-header">📜 Chroniques du Combat</div>
        <div class="log-scroll">
            <?php foreach (array_reverse($combats ?? []) as $combat): ?>
                <?php
                $logClass = 'info';
                if (str_contains($combat, 'COUP CRITIQUE')) {
                    $logClass = 'attack critical';
                } elseif (str_contains($combat, 'COUP SPÉCIAL')) {
                    $logClass = 'special';
                } elseif (str_contains($combat, 'fin de la partie')) {
                    $logClass = 'victory';
                } elseif (str_contains($combat, 'se soigne')) {
                    $logClass = 'heal';
                } elseif (str_contains($combat, $player['name'])) {
                    $logClass = 'attack';
                } else {
                    $logClass = 'enemy-attack';
                }
                ?>
                <div class="log-line <?= $logClass ?>"><?= htmlspecialchars($combat) ?></div>
            <?php endforeach; ?>
        </div>
    </div>

</section>
<?php endif; ?>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // --- Sélecteurs : masquer le formulaire si un fighter existant est choisi ---
    var selectPlayer     = document.getElementById("selectPlayer");
    var formPlayer       = document.getElementById("formPlayer");
    var selectAdversaire = document.getElementById("selectAdversaire");
    var formAdversaire   = document.getElementById("formAdversaire");

    function toggleForm(select, form, otherSelect) {
        if (!select || !form) return;
        select.addEventListener("change", function () {
            // Réactiver toutes les options de l'autre select
            if (otherSelect) {
                Array.from(otherSelect.options).forEach(function(opt) {
                    opt.disabled = false;
                });
            }
            if (select.value !== '') {
                form.style.display = 'none';
                Array.from(form.querySelectorAll("input")).forEach(function(i) { i.required = false; });
                // Désactiver la même option dans l'autre select
                if (otherSelect) {
                    var twin = otherSelect.querySelector('option[value="' + select.value + '"]');
                    if (twin) twin.disabled = true;
                }
            } else {
                form.style.display = '';
                Array.from(form.querySelectorAll("input")).forEach(function(i) { i.required = true; });
            }
        });
    }

    toggleForm(selectPlayer,     formPlayer,     selectAdversaire);
    toggleForm(selectAdversaire, formAdversaire, selectPlayer);

    // --- Sons ---
    var submitFight = document.querySelector("[name=fight]");
    var alreadyPlaySongFight = false;
    if (submitFight) {
        submitFight.addEventListener("click", function (event) {
            if (alreadyPlaySongFight) return true;
            event.preventDefault();
            var fightSong = document.getElementById("fight-song");
            fightSong.play();
            alreadyPlaySongFight = true;
            setTimeout(function () { submitFight.click(); }, 500);
        });
    }

    var submitAttaque = document.querySelector("[name=attaque]");
    var alreadyPlaySong = false;
    if (submitAttaque) {
        submitAttaque.addEventListener("click", function (event) {
            if (alreadyPlaySong) return true;
            event.preventDefault();
            var player     = document.querySelector("#player");
            var adversaire = document.querySelector("#adversaire");
            if (player)     player.classList.add("hit");
            if (adversaire) adversaire.classList.add("hit");
            setTimeout(function () {
                if (player)     player.classList.remove("hit");
                if (adversaire) adversaire.classList.remove("hit");
            }, 600);
            var hadoukenSong = document.getElementById("hadoudken-song");
            hadoukenSong.play();
            alreadyPlaySong = true;
            setTimeout(function () { submitAttaque.click(); }, 1000);
        });
    }

    var submitRestart = document.querySelector("[name=restart]");
    var alreadyPlaySongRestart = false;
    if (submitRestart) {
        submitRestart.addEventListener("click", function (event) {
            if (alreadyPlaySongRestart) return true;
            event.preventDefault();
            var fatalitySong = document.getElementById("fatality-song");
            fatalitySong.play();
            alreadyPlaySongRestart = true;
            setTimeout(function () { submitRestart.click(); }, 2000);
        });
    }

    // Scroll le log en bas automatiquement
    var logScroll = document.querySelector(".log-scroll");
    if (logScroll) { logScroll.scrollTop = logScroll.scrollHeight; }
});
</script>

</body>
</html>
