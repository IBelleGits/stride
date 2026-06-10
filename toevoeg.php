<?php
session_start();
require 'config.php';

if (!isset($_SESSION['loggedInUser'])) {
    header("Location: inlog_pg.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $naam = trim($_POST['naam'] ?? '');
    $categorie = trim($_POST['categorie'] ?? '');
    $cal = (int)($_POST['calorieen'] ?? 0);

    if ($naam === '' || $categorie === '' || $cal <= 0) {
        $_SESSION['flash_error'] = "Vul alle velden correct in.";
        header("Location: teller_pg.php");
        exit;
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO ingredienten (naam, categorie, calorieen_per_100g)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$naam, $categorie, $cal]);

        $_SESSION['flash_success'] = "Product succesvol toegevoegd!";
    } catch (Exception $e) {
        $_SESSION['flash_error'] = "Er ging iets mis bij het opslaan.";
    }
}

header("Location: teller_pg.php");
exit;