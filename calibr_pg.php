<?php
session_start();
if (!isset($_SESSION['loggedInUser'])) { header("Location: inlog_pg.php"); exit; }

require 'config.php';

$userId  = $_SESSION['loggedInUser'];
$success = '';
$error   = '';

$stmt = $pdo->prepare("SELECT * FROM gebruiker_profiel WHERE gebruiker_id = ?");
$stmt->execute([$userId]);
$profiel = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $leeftijd        = (int)$_POST['leeftijd'];
    $lengte          = (int)$_POST['lengte'];
    $gewicht         = (float)$_POST['gewicht'];
    $geslacht        = $_POST['geslacht'];
    $activiteit      = $_POST['activiteit'];
    $calorie_doel    = (int)$_POST['calorie_doel'];
    $gewicht_doel    = (float)$_POST['gewicht_doel'];

    if ($leeftijd < 1 || $leeftijd > 120 || $lengte < 50 || $lengte > 250 || $gewicht < 20 || $gewicht > 400) {
        $error = "Controleer de ingevoerde waarden.";
    } else {
        if ($profiel) {
            $stmt = $pdo->prepare("
                UPDATE gebruiker_profiel
                SET leeftijd=?, lengte=?, gewicht=?, geslacht=?,
                    activiteit=?, calorie_doel=?, gewicht_doel=?
                WHERE gebruiker_id=?
            ");
            $stmt->execute([$leeftijd, $lengte, $gewicht, $geslacht,
                            $activiteit, $calorie_doel, $gewicht_doel, $userId]);
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO gebruiker_profiel
                    (gebruiker_id, leeftijd, lengte, gewicht, geslacht, activiteit, calorie_doel, gewicht_doel)
                VALUES (?,?,?,?,?,?,?,?)
            ");
            $stmt->execute([$userId, $leeftijd, $lengte, $gewicht, $geslacht,
                            $activiteit, $calorie_doel, $gewicht_doel]);
        }
        $success = "Profiel opgeslagen!";
        $stmt = $pdo->prepare("SELECT * FROM gebruiker_profiel WHERE gebruiker_id = ?");
        $stmt->execute([$userId]);
        $profiel = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

$multipliers = [
    'sedentair'   => 1.2,
    'licht'       => 1.375,
    'matig'       => 1.55,
    'actief'      => 1.725,
    'zeer_actief' => 1.9,
];
$activityLabels = [
    'sedentair'   => 'Sedentair (nauwelijks beweging)',
    'licht'       => 'Licht actief (1-3 dagen/week)',
    'matig'       => 'Matig actief (3-5 dagen/week)',
    'actief'      => 'Actief (6-7 dagen/week)',
    'zeer_actief' => 'Zeer actief (intensief + fysiek werk)',
];
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Calibratie — Stride</title>
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
            <li class="nav-item active"><a class="nav-link" href="#">Calibratie</a></li>
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

    <h2 class="section-title">Calibratie</h2>

    <?php if ($success): ?>
        <div class="alert-stride alert-stride--success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert-stride alert-stride--error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card-stride">
        <h4 style="margin-bottom:20px;font-weight:700;">Caloriebehoefte berekenen</h4>
        <p style="color:#666;font-size:14px;margin-bottom:20px;">
            Vul je gegevens in. De calculator gebruikt de <strong>Mifflin-St Jeor</strong>-formule
            om je basaalmetabolisme (BMR) en dagelijkse energiebehoefte (TDEE) te berekenen.
        </p>

        <form class="form-stride" id="calcForm">
            <div class="form-row-3">
                <div>
                    <label for="calc_leeftijd">Leeftijd</label>
                    <input type="number" id="calc_leeftijd" min="1" max="120"
                           placeholder="bijv. 25"
                           value="<?= htmlspecialchars($profiel['leeftijd'] ?? '') ?>">
                </div>
                <div>
                    <label for="calc_lengte">Lengte (cm)</label>
                    <input type="number" id="calc_lengte" min="50" max="250"
                           placeholder="bijv. 175"
                           value="<?= htmlspecialchars($profiel['lengte'] ?? '') ?>">
                </div>
                <div>
                    <label for="calc_gewicht">Gewicht (kg)</label>
                    <input type="number" id="calc_gewicht" min="20" max="400" step="0.1"
                           placeholder="bijv. 70"
                           value="<?= htmlspecialchars($profiel['gewicht'] ?? '') ?>">
                </div>
            </div>

            <div class="form-row-2">
                <div>
                    <label for="calc_geslacht">Geslacht</label>
                    <select id="calc_geslacht">
                        <option value="man"   <?= ($profiel['geslacht'] ?? '') === 'man'   ? 'selected' : '' ?>>Man</option>
                        <option value="vrouw" <?= ($profiel['geslacht'] ?? '') === 'vrouw' ? 'selected' : '' ?>>Vrouw</option>
                    </select>
                </div>
                <div>
                    <label for="calc_activiteit">Activiteitsniveau</label>
                    <select id="calc_activiteit">
                        <?php foreach ($activityLabels as $key => $label): ?>
                            <option value="<?= $key ?>"
                                <?= ($profiel['activiteit'] ?? '') === $key ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <button type="button" class="btn-stride" onclick="bereken()">Bereken</button>
        </form>

        <div class="calc-result" id="calcResult">
            <div class="calc-result__row">
                <span class="calc-result__label">Basaalmetabolisme (BMR)</span>
                <span class="calc-result__value" id="res_bmr">—</span>
            </div>
            <div class="calc-result__row">
                <span class="calc-result__label">Dagelijkse energiebehoefte (TDEE)</span>
                <span class="calc-result__value" id="res_tdee">—</span>
            </div>
            <div class="calc-result__row">
                <span class="calc-result__label">Om af te vallen</span>
                <span class="calc-result__value" id="res_afval">—</span>
            </div>
            <div class="calc-result__row">
                <span class="calc-result__label">Om aan te komen</span>
                <span class="calc-result__value" id="res_aan">—</span>
            </div>
            <div style="margin-top:14px;">
                <button type="button" class="btn-stride" onclick="gebruikAlsDoelTdee()">
                    Gebruik TDEE als dagdoel ↓
                </button>
                <button type="button" class="btn-stride" onclick="gebruikAlsDoelAfvallen()">
                    Gebruik afval hoeveelheid als dagdoel ↓
                </button>
                <button type="button" class="btn-stride" onclick="gebruikAlsDoelAankomen()">
                    Gebruik aankom hoeveelheid als dagdoel ↓
                </button>
            </div>
        </div>
    </div>

    <div class="card-stride">
        <h4 style="margin-bottom:20px;font-weight:700;">Mijn doelen opslaan</h4>

        <form class="form-stride" method="POST">
            <input type="hidden" name="leeftijd"   id="save_leeftijd">
            <input type="hidden" name="lengte"     id="save_lengte">
            <input type="hidden" name="gewicht"    id="save_gewicht">
            <input type="hidden" name="geslacht"   id="save_geslacht">
            <input type="hidden" name="activiteit" id="save_activiteit">

            <div class="form-row-2">
                <div>
                    <label for="calorie_doel">Dagelijks caloriedoel (kcal)</label>
                    <input type="number" name="calorie_doel" id="calorie_doel"
                           min="500" max="10000" placeholder="bijv. 2000"
                           value="<?= htmlspecialchars($profiel['calorie_doel'] ?? '') ?>" required>
                </div>
                <div>
                    <label for="gewicht_doel">Streefgewicht (kg)</label>
                    <input type="number" name="gewicht_doel" id="gewicht_doel"
                           min="20" max="400" step="0.1" placeholder="bijv. 65"
                           value="<?= htmlspecialchars($profiel['gewicht_doel'] ?? '') ?>">
                </div>
            </div>

            <button type="submit" class="btn-stride" onclick="preSave()">Opslaan</button>
        </form>
    </div>

    <div class="quick-links" style="margin-top:28px;">
        <a class="quick-link-btn" href="home_pg.php">Home</a>
        <a class="quick-link-btn" href="teller_pg.php">+ Log calorieën</a>
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
<script>
const multipliers = {
    sedentair:   1.2,
    licht:       1.375,
    matig:       1.55,
    actief:      1.725,
    zeer_actief: 1.9
};

let lastTdee = null;

function bereken() {
    const leeftijd  = parseFloat(document.getElementById('calc_leeftijd').value);
    const lengte    = parseFloat(document.getElementById('calc_lengte').value);
    const gewicht   = parseFloat(document.getElementById('calc_gewicht').value);
    const geslacht  = document.getElementById('calc_geslacht').value;
    const activiteit= document.getElementById('calc_activiteit').value;

    if (!leeftijd || !lengte || !gewicht) {
        alert('Vul alle velden in.');
        return;
    }

    let bmr;
    if (geslacht === 'man') {
        bmr = 10 * gewicht + 6.25 * lengte - 5 * leeftijd + 5;
    } else {
        bmr = 10 * gewicht + 6.25 * lengte - 5 * leeftijd - 161;
    }

    const tdee = Math.round(bmr * multipliers[activiteit]);
    lastTdee = tdee;
    const afval = tdee - 500;
    lastAfvallen = afval;
    const aan = tdee + 500;
    lastAankomen = aan;

    document.getElementById('res_bmr').textContent  = Math.round(bmr)  + ' kcal/dag';
    document.getElementById('res_tdee').textContent = tdee              + ' kcal/dag';
    document.getElementById('res_afval').textContent= afval             + ' kcal/dag';
    document.getElementById('res_aan').textContent  = aan               + ' kcal/dag';

    document.getElementById('calcResult').classList.add('visible');
}

function gebruikAlsDoelTdee() {
    if (lastTdee) {
        document.getElementById('calorie_doel').value = lastTdee;
        document.getElementById('calorie_doel').scrollIntoView({ behavior: 'smooth' });
    }
}

function gebruikAlsDoelAfvallen() {
    if (lastTdee) {
        document.getElementById('calorie_doel').value = lastAfvallen;
        document.getElementById('calorie_doel').scrollIntoView({ behavior: 'smooth' });
    }
}

function gebruikAlsDoelAankomen() {
    if (lastTdee) {
        document.getElementById('calorie_doel').value = lastAankomen;
        document.getElementById('calorie_doel').scrollIntoView({ behavior: 'smooth' });
    }
}

function preSave() {
    document.getElementById('save_leeftijd').value   = document.getElementById('calc_leeftijd').value;
    document.getElementById('save_lengte').value     = document.getElementById('calc_lengte').value;
    document.getElementById('save_gewicht').value    = document.getElementById('calc_gewicht').value;
    document.getElementById('save_geslacht').value   = document.getElementById('calc_geslacht').value;
    document.getElementById('save_activiteit').value = document.getElementById('calc_activiteit').value;
}

window.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('calc_leeftijd').value) bereken();
});
</script>
</body>
</html>
