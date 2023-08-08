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
$names = json_encode(array_keys($winners));
?>
<html lang="fr">
<head>
    <title>Battle</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="container">
    <?php require "navbar.php" ?>
    <h1 class="card-title text-center">Les Statistiques de combats</h1>
    <hr>
    <div class="row">
        <div class="col-6">Le nombres total de combats : <?= count($fights) ?></div>
        <div class="col-6">Le combatant avec le plus de victoire
            : <?php echo array_key_first($winners); ?> avec <?php echo array_shift($winners); ?></div>
        <div class="col-6">Le combatant avec le plus de défaite : <?php echo array_key_first($defaites); ?></div>
    </div>
    <hr>
    <canvas id="myChart"></canvas>
</div>

<script>
    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= $names ?>,
            datasets: [{
                label: 'Statistiques des combats',
                data: <?= $victoires ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(201, 203, 207, 0.2)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false,
                }
            }
        }
    });
</script>

</body>
</html>