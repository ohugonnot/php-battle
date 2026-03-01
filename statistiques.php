<?php
require_once __DIR__ . '/vendor/autoload.php';
require "repository.php";

$fights = getAllFights();
$fighters = getAllFighters();
$fightersById = [];
foreach ($fighters as $fighter) {
    $fightersById[$fighter["id"]] = $fighter;
}
$winners = [];
$defaites = [];

foreach ($fights as $fight) {
    $id_player = $fight["fighter1"];
    $id_adversaire = $fight["fighter2"];
    $id_winner = $fight["winner"];
    $id_looser = ($id_winner == $id_player) ? $id_adversaire : $id_player;
    $name = $fightersById[$id_winner]['name'] ?? 'Aucun';
    $name_perdant = $fightersById[$id_looser]['name'] ?? 'Aucun';
    $winners[$name] = ($winners[$name] ?? 0) + 1;
    $defaites[$name_perdant] = ($defaites[$name_perdant] ?? 0) + 1;
}
arsort($winners);
arsort($defaites);

$victoires = json_encode(array_values($winners));
$names     = json_encode(array_keys($winners));

$topWinnerName  = array_key_first($winners);
$topWinnerCount = $winners[$topWinnerName] ?? 0;
$topLoserName   = array_key_first($defaites);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chroniques des Combats — Battle of Shadows</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Crimson+Text:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/battle.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php require "navbar.php" ?>

<div class="stats-page">

    <h1 class="stats-title">⚔ Chroniques des Combats ⚔</h1>

    <div class="rune-divider"><span>✦ ᚠ ᚢ ᚦ ✦</span></div>

    <div class="stats-grid">
        <div class="stat-item">
            <span class="stat-value"><?= count($fights) ?></span>
            <span class="stat-label">Combats totaux</span>
        </div>
        <div class="stat-item">
            <span class="stat-value"><?= htmlspecialchars($topWinnerName ?: '—') ?></span>
            <span class="stat-label">Meilleur champion (<?= $topWinnerCount ?> victoires)</span>
        </div>
        <div class="stat-item">
            <span class="stat-value"><?= htmlspecialchars($topLoserName ?: '—') ?></span>
            <span class="stat-label">Plus grande infamie</span>
        </div>
    </div>

    <div class="chart-container">
        <canvas id="myChart"></canvas>
    </div>

</div>

<script>
const ctx = document.getElementById('myChart');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= $names ?>,
        datasets: [{
            label: 'Victoires',
            data: <?= $victoires ?>,
            backgroundColor: [
                'rgba(139, 26, 26, 0.7)',
                'rgba(192, 57, 43, 0.7)',
                'rgba(201, 168, 76, 0.7)',
                'rgba(122, 92, 30, 0.7)',
                'rgba(90, 20, 20, 0.7)',
                'rgba(240, 208, 128, 0.7)',
                'rgba(74, 122, 191, 0.7)',
            ],
            borderColor: [
                'rgba(201, 168, 76, 0.6)',
                'rgba(201, 168, 76, 0.6)',
                'rgba(201, 168, 76, 0.6)',
                'rgba(201, 168, 76, 0.6)',
                'rgba(201, 168, 76, 0.6)',
                'rgba(201, 168, 76, 0.6)',
                'rgba(201, 168, 76, 0.6)',
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                ticks: { color: '#8a7a64', font: { family: 'Crimson Text' } },
                grid:  { color: 'rgba(58, 47, 30, 0.5)' }
            },
            x: {
                ticks: { color: '#d4c5a9', font: { family: 'Cinzel', size: 12 } },
                grid:  { color: 'rgba(58, 47, 30, 0.3)' }
            }
        },
        plugins: {
            legend: { display: false },
            tooltip: {
                bodyFont: { family: 'Crimson Text', size: 14 },
                titleFont: { family: 'Cinzel', size: 13 },
                backgroundColor: '#12100e',
                borderColor: '#5a4520',
                borderWidth: 1,
                titleColor: '#c9a84c',
                bodyColor: '#d4c5a9',
            }
        }
    }
});
</script>

</body>
</html>
