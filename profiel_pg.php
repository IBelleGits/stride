<?php
session_start();
if (!isset($_SESSION['loggedInUser'])) { header("Location: inlog_pg.php"); exit; }

require 'config.php';

$userId  = $_SESSION['loggedInUser'];
$success = '';
$error   = '';

$stmt = $pdo->prepare("SELECT username, email FROM gebruikers WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM gebruiker_profiel WHERE gebruiker_id = ?");
$stmt->execute([$userId]);
$profiel = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_account'])) {
    $newUsername = trim($_POST['username']);
    $newEmail    = trim($_POST['email']);

    if (!$newUsername || !filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $error = "Vul een geldige gebruikersnaam en e-mailadres in.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM gebruikers WHERE (username = ? OR email = ?) AND id != ?");
        $stmt->execute([$newUsername, $newEmail, $userId]);
        if ($stmt->fetch()) {
            $error = "Gebruikersnaam of e-mail is al in gebruik.";
        } else {
            $pdo->prepare("UPDATE gebruikers SET username = ?, email = ? WHERE id = ?")
                ->execute([$newUsername, $newEmail, $userId]);
            $user['username'] = $newUsername;
            $user['email']    = $newEmail;
            $success = "Account bijgewerkt!";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $huidig   = $_POST['huidig_wachtwoord'];
    $nieuw    = $_POST['nieuw_wachtwoord'];
    $bevestig = $_POST['bevestig_wachtwoord'];

    $stmt = $pdo->prepare("SELECT password FROM gebruikers WHERE id = ?");
    $stmt->execute([$userId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!password_verify($huidig, $row['password'])) {
        $error = "Huidig wachtwoord is onjuist.";
    } elseif (strlen($nieuw) < 6) {
        $error = "Nieuw wachtwoord moet minimaal 6 tekens bevatten.";
    } elseif ($nieuw !== $bevestig) {
        $error = "Nieuwe wachtwoorden komen niet overeen.";
    } else {
        $hash = password_hash($nieuw, PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE gebruikers SET password = ? WHERE id = ?")
            ->execute([$hash, $userId]);
        $success = "Wachtwoord gewijzigd!";
    }
}

$activityLabels = [
    'sedentair'   => 'Sedentair',
    'licht'       => 'Licht actief',
    'matig'       => 'Matig actief',
    'actief'      => 'Actief',
    'zeer_actief' => 'Zeer actief',
];
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profiel — Stride</title>
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
            <li class="nav-item"><a class="nav-link" href="progres_pg.php">Progressie</a></li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                    <img src="icons/inlog.png" height="30" alt="">
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item disabled" href="#">Profiel</a>
                    <button class="dropdown-item" data-toggle="modal" data-target="#logoutModal">Uitloggen</button>
                </div>
            </li>
        </ul>
    </div>
</nav>

<div class="page-wrapper">

    <h2 class="section-title">Mijn profiel</h2>

    <?php if ($success): ?>
        <div class="alert-stride alert-stride--success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert-stride alert-stride--error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($profiel): ?>
    <div class="card-stride">
        <h5 style="font-weight:700;margin-bottom:18px;">Lichaamsgegevens &amp; doelen</h5>
        <div class="profile-info-grid">
            <div>
                <p class="profile-field__label">Leeftijd</p>
                <p class="profile-field__value"><?= $profiel['leeftijd'] ? $profiel['leeftijd'] . ' jaar' : '—' ?></p>
            </div>
            <div>
                <p class="profile-field__label">Geslacht</p>
                <p class="profile-field__value"><?= $profiel['geslacht'] ? ucfirst($profiel['geslacht']) : '—' ?></p>
            </div>
            <div>
                <p class="profile-field__label">Lengte</p>
                <p class="profile-field__value"><?= $profiel['lengte'] ? $profiel['lengte'] . ' cm' : '—' ?></p>
            </div>
            <div>
                <p class="profile-field__label">Gewicht</p>
                <p class="profile-field__value"><?= $profiel['gewicht'] ? number_format($profiel['gewicht'], 1, ',', '.') . ' kg' : '—' ?></p>
            </div>
            <div>
                <p class="profile-field__label">Activiteitsniveau</p>
                <p class="profile-field__value"><?= $activityLabels[$profiel['activiteit']] ?? '—' ?></p>
            </div>
            <div>
                <p class="profile-field__label">Caloriedoel</p>
                <p class="profile-field__value"><?= $profiel['calorie_doel'] ? $profiel['calorie_doel'] . ' kcal/dag' : '—' ?></p>
            </div>
            <div>
                <p class="profile-field__label">Streefgewicht</p>
                <p class="profile-field__value"><?= $profiel['gewicht_doel'] ? number_format($profiel['gewicht_doel'], 1, ',', '.') . ' kg' : '—' ?></p>
            </div>
        </div>
        <div style="margin-top:18px;">
            <a href="calibr_pg.php" class="quick-link-btn">Gegevens aanpassen →</a>
        </div>
    </div>
    <?php else: ?>
    <div class="alert-stride alert-stride--info">
        Je hebt nog geen lichaamsgegevens ingesteld.
        <a href="calibr_pg.php">Ga naar Calibratie →</a>
    </div>
    <?php endif; ?>

    <div class="card-stride">
        <h5 style="font-weight:700;margin-bottom:18px;">Account gegevens</h5>
        <form class="form-stride" method="POST">
            <input type="hidden" name="update_account" value="1">
            <div class="form-row-2">
                <div>
                    <label for="username">Gebruikersnaam</label>
                    <input type="text" name="username" id="username"
                           value="<?= htmlspecialchars($user['username']) ?>" required>
                </div>
                <div>
                    <label for="email">E-mailadres</label>
                    <input type="email" name="email" id="email"
                           value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
            </div>
            <button type="submit" class="btn-stride">Opslaan</button>
        </form>
    </div>

    <div class="card-stride">
        <h5 style="font-weight:700;margin-bottom:18px;">Wachtwoord wijzigen</h5>
        <form class="form-stride" method="POST">
            <input type="hidden" name="update_password" value="1">
            <div>
                <label for="huidig_wachtwoord">Huidig wachtwoord</label>
                <input type="password" name="huidig_wachtwoord" id="huidig_wachtwoord" required>
            </div>
            <div class="form-row-2">
                <div>
                    <label for="nieuw_wachtwoord">Nieuw wachtwoord</label>
                    <input type="password" name="nieuw_wachtwoord" id="nieuw_wachtwoord"
                           minlength="6" required>
                </div>
                <div>
                    <label for="bevestig_wachtwoord">Bevestig nieuw wachtwoord</label>
                    <input type="password" name="bevestig_wachtwoord" id="bevestig_wachtwoord" required>
                </div>
            </div>
            <button type="submit" class="btn-stride">Wachtwoord wijzigen</button>
        </form>
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
