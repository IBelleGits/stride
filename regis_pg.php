<?php

session_start();

$host = 'localhost';
$db   = 'stride';
$user = 'root';
$pass = '';

$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Ongeldig e-mailadres";
    } else {

        $stmt = $pdo->prepare("SELECT id FROM gebruikers WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);

        if ($stmt->fetch()) {
            $error = "Gebruikersnaam of e-mail bestaat al";
        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
                INSERT INTO gebruikers (username, email, password)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$username, $email, $hashedPassword]);

            $_SESSION['loggedInUser'] = $pdo->lastInsertId();

            header("Location: home_pg.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Registreren</title>
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
                    <a class="dropdown-item" href="inlog_pg.php">Inloggen</a>
                    <a class="dropdown-item disabled" href="#">Registreren</a>
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

            <h1>Maak een account aan!</h1>

            <?php if (!empty($error)) : ?>
                <p class="error">
                    <?= $error ?>
                </p>
            <?php endif; ?>

            <form method="POST">

                <input type="text" name="username" placeholder="Gebruikersnaam" required>

                <input type="email" name="email" placeholder="E-mail" required>

                <input type="password" name="password" placeholder="Wachtwoord" required>

                <button type="submit">Registreren</button>

            </form>

            <div class="registreren">
                <p>Heb je al een account?</p>

                <a class="register-btn" href="inlog_pg.php">
                    Log hier in!
                </a>
            </div>

        </div>

    </div>

</body>
</html>