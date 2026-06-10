<?php
session_start();
if (!isset($_SESSION['loggedInUser'])) { header("Location: inlog_pg.php"); exit; }

require 'config.php';

$userId  = $_SESSION['loggedInUser'];
$today   = date('Y-m-d');
$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ingredient_id'])) {

    $ids    = $_POST['ingredient_id'];
    $grams  = $_POST['hoeveelheid'];

    if (empty($ids) || count($ids) === 0) {
        $error = "Voeg minstens één ingredient toe.";
    } else {

        $stmt = $pdo->prepare("SELECT id FROM calorie_log WHERE gebruiker_id = ? AND datum = ?");
        $stmt->execute([$userId, $today]);
        $log = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$log) {
            $stmt = $pdo->prepare("INSERT INTO calorie_log (gebruiker_id, datum, totaal_calorieen) VALUES (?,?,0)");
            $stmt->execute([$userId, $today]);
            $logId = $pdo->lastInsertId();
        } else {
            $logId = $log['id'];
            $pdo->prepare("DELETE FROM calorie_log_items WHERE log_id = ?")->execute([$logId]);
        }

        $totaal = 0;

        foreach ($ids as $i => $ingId) {
            $ingId = (int)$ingId;
            $gram  = (float)$grams[$i];
            if ($gram <= 0) continue;

            $stmt = $pdo->prepare("SELECT calorieen_per_100g FROM ingredienten WHERE id = ?");
            $stmt->execute([$ingId]);
            $ing = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$ing) continue;

            $kcal = (int)round(($ing['calorieen_per_100g'] * $gram) / 100);
            $totaal += $kcal;

            $stmt = $pdo->prepare("
                INSERT INTO calorie_log_items (log_id, ingredient_id, hoeveelheid_gram, calorieen)
                VALUES (?,?,?,?)
            ");
            $stmt->execute([$logId, $ingId, $gram, $kcal]);
        }

        $pdo->prepare("UPDATE calorie_log SET totaal_calorieen = ? WHERE id = ?")
            ->execute([$totaal, $logId]);

        $success = "Log opgeslagen! Totaal vandaag: {$totaal} kcal";
    }
}

$ingredienten = $pdo->query("SELECT id, naam, calorieen_per_100g, categorie FROM ingredienten ORDER BY categorie, naam")
                    ->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT cli.ingredient_id, i.naam, i.calorieen_per_100g, cli.hoeveelheid_gram, cli.calorieen
    FROM calorie_log cl
    JOIN calorie_log_items cli ON cli.log_id = cl.id
    JOIN ingredienten i        ON i.id = cli.ingredient_id
    WHERE cl.gebruiker_id = ? AND cl.datum = ?
");
$stmt->execute([$userId, $today]);
$todayItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT calorie_doel FROM gebruiker_profiel WHERE gebruiker_id = ?");
$stmt->execute([$userId]);
$profiel = $stmt->fetch(PDO::FETCH_ASSOC);
$goal = $profiel['calorie_doel'] ?? null;
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Teller — Stride</title>
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
            <li class="nav-item active"><a class="nav-link" href="#">Teller</a></li>
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

    <h2 class="section-title">Calorieteller — <?= date('d-m-Y') ?></h2>

    <?php if (!empty($success)): ?>
        <div class="alert-stride alert-stride--success">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert-stride alert-stride--error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert-stride alert-stride--success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert-stride alert-stride--error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!$goal): ?>
        <div class="alert-stride alert-stride--info">
            Je hebt nog geen caloriedoel ingesteld.
            <a href="calibr_pg.php">Stel je doel in via Calibratie →</a>
        </div>
    <?php endif; ?>

    <div class="card-stride">

        <h5 style="font-weight:700;margin-bottom:16px;">Calorie Tracker</h5>

        <div class="ingredient-adder form-stride">
            <div>
                <label for="ing_select">Product</label>
                <select style="margin-bottom: 16px;" id="ing_select">
                    <option value="" disabled selected hidden>— Kies een product —</option>
                    <?php
                    $currentCat = '';
                    foreach ($ingredienten as $ing):
                        if ($ing['categorie'] !== $currentCat):
                            if ($currentCat !== '') echo '</optgroup>';
                            echo '<optgroup label="' . htmlspecialchars($ing['categorie']) . '">';
                            $currentCat = $ing['categorie'];
                        endif;
                    ?>
                        <option value="<?= $ing['id'] ?>"
                                data-cal="<?= $ing['calorieen_per_100g'] ?>"
                                data-naam="<?= htmlspecialchars($ing['naam']) ?>">
                            <?= htmlspecialchars($ing['naam']) ?>
                            (<?= $ing['calorieen_per_100g'] ?> kcal/100g)
                        </option>
                    <?php endforeach; if ($currentCat !== '') echo '</optgroup>'; ?>
                </select>
            </div>
            <div>
                <label for="ing_gram">Gram</label>
                <input type="number" id="ing_gram" min="1" max="5000" placeholder="100">
            </div>
            <div style="padding-bottom:16px;">
                <button type="button" class="btn-stride" onclick="voegToe()">+ Toevoegen</button>
            </div>
        </div>

        <div class="total-bar">
            <span class="total-bar__label">Totaal vandaag</span>
            <span class="total-bar__value">
                <span id="total_kcal">0</span> kcal
                <?php if ($goal): ?>
                    <span style="font-size:14px;color:#888;font-weight:400;">
                        / <?= $goal ?> doel
                    </span>
                <?php endif; ?>
            </span>
        </div>

        <?php if ($goal): ?>
            <div class="calorie-bar-wrap" style="margin-bottom:20px;">
                <div class="calorie-bar-fill" id="progress_bar" style="width:0%"></div>
            </div>
        <?php endif; ?>

        <form method="POST" id="logForm">
            <table class="ingredient-table" id="ing_table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Gram</th>
                        <th>kcal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="ing_body">
                </tbody>
            </table>

            <div id="hidden_inputs"></div>

            <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
                <button type="submit" class="btn-stride" id="saveBtn" disabled>
                    Log opslaan
                </button>
                <button type="button" class="btn-stride"
                        style="background:#f0e0ff;color:#7c47b8;"
                        onclick="leegMaken()">
                    Leegmaken
                </button>
            </div>
        </form>

    </div>

    <div class="card-stride" style="margin-top:20px;">
        <h5 style="font-weight:700; margin-bottom:12px;">
            + Nieuw product toevoegen
        </h5>

        <?php if (!empty($_SESSION['flash_success'])): ?>
            <div class="alert-stride alert-stride--success alert-dismissible">
                <?= htmlspecialchars($_SESSION['flash_success']) ?>
                <button type="button" class="alert-close" onclick="this.parentElement.remove()">×</button>
            </div>
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['flash_error'])): ?>
            <div class="alert-stride alert-stride--error alert-dismissible">
                <?= htmlspecialchars($_SESSION['flash_error']) ?>
                <button type="button" class="alert-close" onclick="this.parentElement.remove()">×</button>
            </div>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>

        <form method="POST" action="toevoeg.php" class="form-stride">
            <input type="hidden" name="nieuw_product" value="1">

            <label>Naam</label>
            <input type="text" name="naam" placeholder="Bijv. Cola Zero" required>

            <label>Calorieën per 100g/ml</label>
            <input type="number" name="calorieen" placeholder="Bijv. 42" required min="1">

            <label>Categorie</label>
            <select name="categorie" required>
                <option value="" disabled selected hidden>— Kies een categorie —</option>
                <option value="Beleg">Beleg</option>
                <option value="Drinken">Drinken</option>
                <option value="Eiwit">Eiwit</option>
                <option value="Fastfood">Fastfood</option>
                <option value="Fruit">Fruit</option>
                <option value="Groente">Groente</option>
                <option value="Snack">Snack</option>
                <option value="Snoep">Snoep</option>
                <option value="Zetmeel">Zetmeel</option>
                <option value="Zuivel">Zuivel</option>
                <option value="Overig">Overig</option>
            </select>

            <button type="submit" class="btn-stride">
                Product toevoegen
            </button>

        </form>
    </div>

    <div class="quick-links" style="margin-top:28px;">
        <a class="quick-link-btn" href="home_pg.php">Home</a>
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
<script>
const GOAL = <?= $goal ? (int)$goal : 'null' ?>;

let items = <?= json_encode(array_map(fn($r) => [
    'id'    => (int)$r['ingredient_id'],
    'naam'  => $r['naam'],
    'cal'   => (float)$r['calorieen_per_100g'],
    'gram'  => (float)$r['hoeveelheid_gram'],
    'kcal'  => (int)$r['calorieen'],
], $todayItems)) ?>;

function voegToe() {
    const sel  = document.getElementById('ing_select');
    const gram = parseFloat(document.getElementById('ing_gram').value);

    if (!sel.value || !gram || gram <= 0) {
        alert('Kies een product en voer een hoeveelheid in.');
        return;
    }

    const opt  = sel.options[sel.selectedIndex];
    const cal  = parseFloat(opt.dataset.cal);
    const kcal = Math.round((cal * gram) / 100);

    items.push({
        id:   parseInt(sel.value),
        naam: opt.dataset.naam,
        cal:  cal,
        gram: gram,
        kcal: kcal,
    });

    sel.value = '';
    document.getElementById('ing_gram').value = '';

    render();
}

function verwijder(idx) {
    items.splice(idx, 1);
    render();
}

function leegMaken() {
    if (items.length === 0 || confirm('Weet je zeker dat je de lijst wilt leegmaken?')) {
        items = [];
        render();
    }
}

function render() {
    const tbody  = document.getElementById('ing_body');
    const hidden = document.getElementById('hidden_inputs');
    tbody.innerHTML  = '';
    hidden.innerHTML = '';

    let totaal = 0;

    items.forEach((item, i) => {
        totaal += item.kcal;

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${escHtml(item.naam)}</td>
            <td>${item.gram} g</td>
            <td><strong>${item.kcal}</strong> kcal</td>
            <td>
                <button type="button" class="btn-remove" onclick="verwijder(${i})" title="Verwijderen">✕</button>
            </td>
        `;
        tbody.appendChild(tr);

        hidden.innerHTML += `<input type="hidden" name="ingredient_id[]" value="${item.id}">`;
        hidden.innerHTML += `<input type="hidden" name="hoeveelheid[]"   value="${item.gram}">`;
    });

    document.getElementById('total_kcal').textContent = totaal;

    if (GOAL) {
        const pct  = Math.min((totaal / GOAL) * 100, 100);
        const bar  = document.getElementById('progress_bar');
        bar.style.width = pct + '%';
        bar.classList.remove('calorie-bar-fill--over', 'calorie-bar-fill--warning');
        if (totaal > GOAL)              bar.classList.add('calorie-bar-fill--over');
        else if (totaal > GOAL * 0.85)  bar.classList.add('calorie-bar-fill--warning');
    }

    document.getElementById('saveBtn').disabled = (items.length === 0);
}

function escHtml(str) {
    return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

render();
</script>
</body>
</html>
