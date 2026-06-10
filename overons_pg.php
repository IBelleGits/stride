<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Over ons — Stride</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .about-expl {
            text-align: center;
            padding: 60px 20px 40px;
        }

        .about-expl h1 {
            font-size: 36px;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .about-expl p, .about-expl i {
            font-size: 16px;
            color: #666;
            max-width: 610px;
            margin: 5px auto;
            line-height: 1.6;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <a class="navbar-brand" href="intro_pg.php">
        <img src="logo/stride_logo.png" height="40" alt="Stride">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nav">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="intro_pg.php">Introductie</a></li>
            <li class="nav-item"><a class="nav-link" href="overstride_pg.php">Over Stride</a></li>
            <li class="nav-item active"><a class="nav-link" href="#">Over ons</a></li>
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

<div class="page-wrapper--wide page-wrapper">

    <div class="about-hero">
        <img src="logo/stride_logo_name.png" alt="Stride logo">
        <h1>Over ons</h1>
        <p>
            Stride is gebouwd door een klein team van studenten die geloven dat gezond
            leven toegankelijk moet zijn voor iedereen. Eenvoudig, eerlijk en effectief.
        </p>
    </div>

    <h2 class="section-title" style="margin-top:40px;">Gemaakt door:</h2>

    <div style="align-items: center;">
        <div class="team-card">
            <div class="team-card__avatar">IG</div>
            <h4>Isabelle Gits</h4>
            <p>Ontwikkelaar &amp; Oprichter</p>
        </div>
    </div>

    <div class="about-expl">
        <h1>Waarom Stride?</h1>
        <p>Zelf heb ik het altijd lastig gevonden om mijn gewicht goed onder controle te houden.</p>
        <p>Ik heb meerdere apps en sites geprobeerd om dit meer onder controle te hebben maar ik liep vaak tegen hetzelfde probleem aan.</p>
        <p>Alle daadwerkelijk handige onderdelen van deze apps waren niet gratis te gebruiken.</p>
        <p>Ik ben zelf nog student en heb dus niet de financiële mogelijkheid om hier geld voor te betalen.</p>
        <p>Nu kreeg ik voor school een opdracht om een volledig functionele website of app te bouwen die gebruik maken van specifieke talen en onderdelen.</p>
        <p>Eerst wist ik niet goed wat ik kon gaan bouwen, totdat ik begon na te denken over iets wat mij in mijn persoonlijke leven heel veel zou kunnen helpen.</p>
        <p>Uiteindelijk kwam ik uit bij een volledige calorie tracker die makkelijk te gebruiken is en geen cent kost!</p>
        <p>Ik hoop dat Stride anderen even goed kan helpen zoals dat het mij heeft geholpen.</p>
        <div style="margin-top: 20px;"><i>- Isabelle Gits</i></div>
    </div>

    <div class="card-stride" style="margin-top:40px;text-align:center;">
        <h4 style="font-weight:700;margin-bottom:10px;">Neem contact op</h4>
        <p style="color:#666;margin-bottom:6px;">Vragen of feedback? Stuur ons een e-mail:</p>
        <a href="mailto:info@stride.nl" style="font-size:16px;font-weight:600;color:#a96de0;">
            info@stride.nl
        </a>
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
            <a href="intro_pg.php">Introductie</a>
            <a href="overstride_pg.php">Over Stride</a>
            <a href="#">Over ons</a>

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
