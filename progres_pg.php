<?php
session_start();
if (!isset($_SESSION['loggedInUser'])) { header("Location: inlog_pg.php"); exit; }

require 'config.php';

$userId = $_SESSION['loggedInUser'];

$stmt = $pdo->prepare("SELECT calorie_doel FROM gebruiker_profiel WHERE gebruiker_id = ?");
$stmt->execute([$userId]);
$profiel = $stmt->fetch(PDO::FETCH_ASSOC);
$goal = $profiel['calorie_doel'] ?? null;

$stmt = $pdo->prepare("
    SELECT
        cl.datum,
        cl.totaal_calorieen,
        GROUP_CONCAT(
            CONCAT(i.naam, ' (', cli.hoeveelheid_gram, 'g)')
            ORDER BY i.naam SEPARATOR '||'
        ) AS items_str
    FROM calorie_log cl
    LEFT JOIN calorie_log_items cli ON cli.log_id = cl.id
    LEFT JOIN ingredienten i        ON i.id = cli.ingredient_id
    WHERE cl.gebruiker_id = ?
    GROUP BY cl.id
    ORDER BY cl.datum DESC
    LIMIT 30
");
$stmt->execute([$userId]);
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalDays  = count($logs);
$totalKcal  = array_sum(array_column($logs, 'totaal_calorieen'));
$avgKcal    = $totalDays > 0 ? round($totalKcal / $totalDays) : 0;
$daysUnder  = $goal ? count(array_filter($logs, fn($l) => $l['totaal_calorieen'] <= $goal)) : null;
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Progressie — Stride</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <a class="navbar-brand" href="home_pg.php">
        <img src="logo/stride_logo.png" height="40" alt="Stride">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nav">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="home_pg.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="teller_pg.php">Teller</a></li>
            <li class="nav-item"><a class="nav-link" href="calibr_pg.php">Calibratie</a></li>
            <li class="nav-item active"><a class="nav-link" href="#">Progressie</a></li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                    <img src="icons/inlog.png" height="30" alt="">
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="profiel_pg.php">Profiel</a>
                    <button class="dropdown-item" data-toggle="modal" data-target="#logoutModal">Uitloggen</button>
                </div>
            </li>
        </ul>
    </div>
</nav>

<div class="page-wrapper">

    <h2 class="section-title">Progressie — laatste 30 dagen</h2>

    <div class="dashboard-grid" style="margin-bottom:28px;">
        <div class="stat-card">
            <span class="stat-card__label">Dagen gelogd</span>
            <span class="stat-card__value"><?= $totalDays ?></span>
            <span class="stat-card__sub">van de afgelopen 30 dagen</span>
        </div>
        <div class="stat-card">
            <span class="stat-card__label">Gemiddeld per dag</span>
            <span class="stat-card__value"><?= $avgKcal ?></span>
            <span class="stat-card__sub">kcal</span>
        </div>
        <?php if ($goal): ?>
        <div class="stat-card">
            <span class="stat-card__label">Dagen onder doel</span>
            <span class="stat-card__value"><?= $daysUnder ?></span>
            <span class="stat-card__sub">van <?= $goal ?> kcal doel</span>
        </div>
        <?php endif; ?>
        <div class="stat-card">
            <span class="stat-card__label">Totaal geconsumeerd</span>
            <span class="stat-card__value"><?= number_format($totalKcal, 0, ',', '.') ?></span>
            <span class="stat-card__sub">kcal in totaal</span>
        </div>
    </div>

    <div class="card-stride">
        <?php if (empty($logs)): ?>
            <div class="alert-stride alert-stride--info">
                Nog geen activiteiten gelogd.
                <a href="teller_pg.php">Start je eerste log →</a>
            </div>
        <?php else: ?>
        <table class="history-table">
            <thead>
                <tr>
                    <th>Datum</th>
                    <th>Totaal</th>
                    <?php if ($goal): ?><th>Status</th><?php endif; ?>
                    <th>Wat gegeten</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($logs as $log):
                $kcal  = (int)$log['totaal_calorieen'];
                $datum = date('d-m-Y', strtotime($log['datum']));
                $items = $log['items_str'] ? explode('||', $log['items_str']) : [];

                if ($goal) {
                    if ($kcal <= $goal)
                        $badge = '<span class="badge-under">Onder doel</span>';
                    else
                        $badge = '<span class="badge-over">Boven doel</span>';
                }
            ?>
                <tr>
                    <td><?= $datum ?></td>
                    <td><strong><?= $kcal ?> kcal</strong></td>
                    <?php if ($goal): ?>
                    <td><?= $badge ?></td>
                    <?php endif; ?>
                    <td>
                        <?php if ($items): ?>
                            <ul class="items-list">
                                <?php foreach ($items as $item): ?>
                                    <li>· <?= htmlspecialchars($item) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <span style="color:#aaa;">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

    <div class="quick-links" style="margin-top:28px;">
        <a class="quick-link-btn" href="home_pg.php">Home</a>
        <a class="quick-link-btn" href="teller_pg.php">+ Log calorieën</a>
        <a class="quick-link-btn" href="calibr_pg.php">Calibratie &amp; doelen</a>
        <a class="quick-link-btn" href="profiel_pg.php">Mijn profiel</a>
    </div>

</div>

<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Uitloggen bevestigen</h5></div>
            <div class="modal-body">Weet je zeker dat je wilt uitloggen?</div>
            <div class="modal-footer">
                <a href="uitlog.php" class="btn btn-danger">Ja, uitloggen</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuleren</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
