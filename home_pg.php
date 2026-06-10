<?php
session_start();
if (!isset($_SESSION['loggedInUser'])) { header("Location: inlog_pg.php"); exit; }

require 'config.php';

$userId = $_SESSION['loggedInUser'];
$today  = date('Y-m-d');

$stmt = $pdo->prepare("SELECT username FROM gebruikers WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT calorie_doel FROM gebruiker_profiel WHERE gebruiker_id = ?");
$stmt->execute([$userId]);
$profiel = $stmt->fetch(PDO::FETCH_ASSOC);
$goal = $profiel['calorie_doel'] ?? null;

$stmt = $pdo->prepare("SELECT totaal_calorieen FROM calorie_log WHERE gebruiker_id = ? AND datum = ?");
$stmt->execute([$userId, $today]);
$todayLog = $stmt->fetch(PDO::FETCH_ASSOC);
$todayKcal = $todayLog ? (int)$todayLog['totaal_calorieen'] : 0;

$monday = date('Y-m-d', strtotime('monday this week'));
$stmt = $pdo->prepare("
    SELECT AVG(totaal_calorieen) as gem, COUNT(*) as dagen
    FROM calorie_log
    WHERE gebruiker_id = ? AND datum BETWEEN ? AND ?
");
$stmt->execute([$userId, $monday, $today]);
$week = $stmt->fetch(PDO::FETCH_ASSOC);
$weekAvg  = $week['gem']  ? (int)round($week['gem'])  : 0;
$weekDays = $week['dagen'] ?? 0;

$stmt = $pdo->prepare("SELECT COUNT(*) FROM calorie_log WHERE gebruiker_id = ?");
$stmt->execute([$userId]);
$totalDays = (int)$stmt->fetchColumn();

$barPct   = $goal && $todayKcal > 0 ? min(round(($todayKcal / $goal) * 100), 100) : 0;
$barOver  = $goal && $todayKcal > $goal;
$barWarn  = $goal && !$barOver && $todayKcal > $goal * 0.85;

$dagNl = ['Sunday'=>'Zondag','Monday'=>'Maandag','Tuesday'=>'Dinsdag',
          'Wednesday'=>'Woensdag','Thursday'=>'Donderdag','Friday'=>'Vrijdag','Saturday'=>'Zaterdag'];
$maandNl = ['January'=>'januari','February'=>'februari','March'=>'maart','April'=>'april',
            'May'=>'mei','June'=>'juni','July'=>'juli','August'=>'augustus',
            'September'=>'september','October'=>'oktober','November'=>'november','December'=>'december'];
$datumStr = $dagNl[date('l')] . ' ' . date('j') . ' ' . $maandNl[date('F')] . ' ' . date('Y');
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home — Stride</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <a class="navbar-brand" href="#">
        <img src="logo/stride_logo.png" height="40" alt="Stride">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nav">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
        <ul class="navbar-nav">
            <li class="nav-item active"><a class="nav-link" href="#">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="teller_pg.php">Teller</a></li>
            <li class="nav-item"><a class="nav-link" href="calibr_pg.php">Calibratie</a></li>
            <li class="nav-item"><a class="nav-link" href="progres_pg.php">Progressie</a></li>
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

    <p class="dashboard-welcome">Welkom terug, <?= htmlspecialchars($user['username']) ?>!</p>
    <p class="dashboard-date"><?= $datumStr ?></p>

    <div class="card-stride" style="margin-bottom:24px;">
        <h5 style="font-weight:700;margin-bottom:4px;">Calorieën vandaag</h5>
        <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:8px;">
            <span style="font-size:40px;font-weight:700;color:#a96de0;"><?= $todayKcal ?> kcal</span>
            <?php if ($goal): ?>
                <span style="font-size:15px;color:#888;">doel: <?= $goal ?> kcal</span>
            <?php else: ?>
                <a href="calibr_pg.php" style="font-size:13px;">Stel een doel in →</a>
            <?php endif; ?>
        </div>

        <?php if ($goal): ?>
            <div class="calorie-bar-wrap">
                <div class="calorie-bar-fill <?= $barOver ? 'calorie-bar-fill--over' : ($barWarn ? 'calorie-bar-fill--warning' : '') ?>"
                     style="width:<?= $barPct ?>%"></div>
            </div>
            <p style="font-size:13px;color:#888;margin-top:6px;">
                <?php if ($barOver): ?>
                    <span style="color:#d9534f;font-weight:600;">
                        <?= $todayKcal - $goal ?> kcal boven je doel
                    </span>
                <?php elseif ($todayKcal === 0): ?>
                    Je hebt vandaag nog niets gelogd.
                <?php else: ?>
                    <?= $goal - $todayKcal ?> kcal resterend voor vandaag
                <?php endif; ?>
            </p>
        <?php endif; ?>
    </div>

    <div class="dashboard-grid">
        <div class="stat-card">
            <span class="stat-card__label">Gemiddelde deze week</span>
            <span class="stat-card__value"><?= $weekAvg ?></span>
            <span class="stat-card__sub">kcal/dag · <?= $weekDays ?> dag<?= $weekDays !== 1 ? 'en' : '' ?> gelogd</span>
        </div>
        <div class="stat-card">
            <span class="stat-card__label">Totaal dagen gelogd</span>
            <span class="stat-card__value"><?= $totalDays ?></span>
            <span class="stat-card__sub">dagen</span>
        </div>
        <?php if ($goal): ?>
        <div class="stat-card">
            <span class="stat-card__label">Jouw caloriedoel</span>
            <span class="stat-card__value"><?= $goal ?></span>
            <span class="stat-card__sub">kcal per dag</span>
        </div>
        <?php endif; ?>
    </div>

    <div class="quick-links" style="margin-top:28px;">
        <a class="quick-link-btn" href="teller_pg.php">+ Log calorieën</a>
        <a class="quick-link-btn" href="calibr_pg.php">Calibratie &amp; doelen</a>
        <a class="quick-link-btn" href="progres_pg.php">Bekijk progressie</a>
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
