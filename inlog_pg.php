<?php

session_start();

$host = 'localhost';
$db   = 'stride';
$user = 'root';
$pass = '';

$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM gebruikers WHERE username = ?");
    $stmt->execute([$username]);

    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        $error = "Onjuiste inloggegevens";
    } else {
        $_SESSION['loggedInUser'] = $user['id'];
        header("Location: home_pg.php");
    exit;
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Inloggen</title>
</head>
<body>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="style.css">

<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <a class="navbar-brand" href="#">
        <img src="logo/stride_logo.png" height="40" alt="Stride">
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">

        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="intro_pg.php">Introductie</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="overstride_pg.php">Over Stride</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="overons_pg.php">Over ons</a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                    <img src="icons/inlog.png" height="30" alt="Account">
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item disabled" href="#">Inloggen</a>
                    <a class="dropdown-item" href="regis_pg.php">Registreren</a>
                </div>
            </li>
        </ul>

    </div>
</nav>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <div class="page-wrapper d-flex justify-content-center">

        <div class="login-container">

            <img class="logo" src="logo/stride_logo_name.png" alt="Stride">

            <h1>Log in!</h1>

            <?php if (!empty($error)) : ?>
                <p class="error">
                    <?= $error ?>
                </p>
            <?php endif; ?>

            <form method="POST">

                <input type="text" name="username" placeholder="gebruikersnaam" required>

                <input type="password" name="password" placeholder="wachtwoord" required>

                <button type="submit">Inloggen</button>

            </form>

            <div class="registreren">
                <p>Nog geen account?</p>

                <a class="register-btn" href="regis_pg.php">
                    Maak een account aan!
                </a>
            </div>

        </div>

    </div>

</body>
</html>