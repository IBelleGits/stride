<?php session_start(); ?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stride — Jouw calorie tracker</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .hero {
            min-height: calc(100vh - 56px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 80px 24px 40px;
        }
        .hero img { 
            width: 200px; 
            margin-bottom: 28px; 
        }
        .hero h1 { 
            font-size: 42px; 
            font-weight: 700; 
            color: #333; 
            margin-bottom: 14px; 
        }
        .hero p  { 
            font-size: 17px; 
            color: #666; 
            max-width: 520px; 
            line-height: 1.65; 
            margin-bottom: 32px; 
        }
        .hero-btns { 
            display: flex; 
            gap: 14px; 
            flex-wrap: wrap; 
            justify-content: center; 
        }
    </style>
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
            <li class="nav-item active"><a class="nav-link" href="#">Introductie</a></li>
            <li class="nav-item"><a class="nav-link" href="overstride_pg.php">Over Stride</a></li>
            <li class="nav-item"><a class="nav-link" href="overons_pg.php">Over ons</a></li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                    <img src="icons/inlog.png" height="30" alt="">
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="inlog_pg.php">Inloggen</a>
                    <a class="dropdown-item" href="regis_pg.php">Registreren</a>
                </div>
            </li>
        </ul>
    </div>
</nav>

<div style="background:#fff;">
    <div class="hero">
        <img src="logo/stride_logo_name.png" alt="Stride">
        <h1>Jouw persoonlijke calorie tracker</h1>
        <p>
            Houd bij wat je eet, bereken je energiebehoefte en volg je progressie.
            Stride maakt gezond leven makkelijk en overzichtelijk.
        </p>
        <div class="hero-btns">
            <a href="regis_pg.php" class="btn-stride" style="font-size: 16px; padding: 14px 36px; text-decoration: none;">
                Begin nu gratis
            </a>
            <a href="inlog_pg.php" class="register-btn" style="font-size: 16px; padding: 14px 36px;">
                Inloggen
            </a>
        </div>
    </div>

    <div style="background:#faf5ff;padding:60px 40px;">
        <div class="about-grid" style="max-width:960px;margin:0 auto;">
            <div class="about-card">
                <div class="about-card__icon">🥗</div>
                <h3>Calorieën tellen</h3>
                <p>Kies ingrediënten, voer grammen in en zie direct je calorie-inname.</p>
            </div>
            <div class="about-card">
                <div class="about-card__icon">⚖️</div>
                <h3>BMR &amp; TDEE calculator</h3>
                <p>Bereken hoeveel calorieën jouw lichaam per dag nodig heeft.</p>
            </div>
            <div class="about-card">
                <div class="about-card__icon">📈</div>
                <h3>Progressie</h3>
                <p>Bekijk je eetgeschiedenis en zie of je je doelen haalt.</p>
            </div>
        </div>
    </div>
</div>

<footer class="stride-footer">

    <div class="stride-footer_grid">

        <div class="stride-footer_col">
            <img src="logo/stride_logo.png" class="stride-footer_logo" alt="Stride">
            <p class="stride-footer_text">
                Stride helpt je inzicht krijgen in je voeding, calorieën en progressie op een simpele en overzichtelijke manier.
            </p>
        </div>

        <div class="stride-footer_col"></div>

        <div class="stride-footer_col">
            <h4>Meer weten?</h4>
            <a href="#">Introductie</a>
            <a href="overstride_pg.php">Over Stride</a>
            <a href="overons_pg.php">Over ons</a>

            <div style="padding-bottom: 16px;"></div>

            <h4>Fout melden</h4>
            <p>Zie je een bug of fout?</p>
            <a href="mailto:support@stride.nl">Meld het hier →</a>
        </div>

        <div class="stride-footer_col">
            <h4>Contactgegevens</h4>
            <p>Email: info@stride.nl</p>
            <p>Telefoonnummer: +31 6 12345678</p>
        </div>
    </div>

    <div class="stride-footer_bottom">
        <img src="logo/stride_name.png" class="stride-footer_brand" alt="Stride Name">
        <span>© Alle rechten voorbehouden — eigendom van Stride</span>
    </div>

</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
