<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Over Stride</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .about-sections {
            background: #f3e8ff;
            border-radius: 14px;
            margin-top: 40px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .about-section {
            padding: 22px 26px;
            border-bottom: 2px solid #cb95fa;
            transition: all 0.25s ease;
        }

        .about-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .about-title h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
        }

        .about-preview {
            margin-top: 10px;
            color: #555;
            line-height: 1.6;
        }

        .about-extra {
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            transition: max-height 0.35s ease, opacity 0.25s ease;
            color: #555;
            line-height: 1.7;
        }

        .about-section.active .about-extra {
            max-height: 500px;
            opacity: 1;
            margin-top: 12px;
        }

        .about-more {
            display: inline-block;
            margin-top: 10px;
            font-size: 13px;
            color: #7c47b8;
            font-weight: 600;
            cursor: pointer;
        }

        .about-more:hover {
            text-decoration: underline;
        }

        .about-icon {
            font-size: 30px;
            margin-right: 6px;
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
            <li class="nav-item active"><a class="nav-link" href="#">Over Stride</a></li>
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

<div class="page-wrapper--wide page-wrapper">

    <div class="about-hero">
        <img src="logo/stride_logo_name.png" alt="Stride logo">
        <h1>Wat is Stride?</h1>
        <p>
            Stride is een <b>gratis</b> persoonlijke calorie- en voedingstracker waarmee je eenvoudig
            bijhoudt wat je eet, je dagelijkse calorie-behoefte berekent en je voortgang
            over tijd kunt volgen.
        </p>
    </div>

    <div class="about-sections">
    <div class="about-section">
        <div class="about-header">
            <div class="about-title">
                <h3><span class="about-icon">🥗</span> Calorieteller</h3>
            </div>

            <span class="about-more">Lees meer...</span>
        </div>

        <p class="about-preview">
            Onze calorieteller is makkelijk te gebruiken <b>en</b> geeft een duidelijk overzicht over hoe je bezig bent.
        </p>

        <div class="about-extra">
            <p>Kies 1 van de producten uit onze database, vul in hoeveel gram je hiervan hebt gegeten en/of gedronken en klik op toevoegen.</p>
            <p>Heb je perongeluk iets toegevoegd wat je toch niet hebt geconsumeerd? Geen probleem! Klik simpelweg op het kruisje naast dat specifieke product en het product wordt weer verwijderd.</p>
            <p>Staat het product wat je wilt kiezen nog niet in onze database? Voeg het product simpel toe aan onze database en help ons met het uitbreiden van alle keuzes!</p>
        </div>
    </div>

    <div class="about-section">
        <div class="about-header">
            <div class="about-title">
                <h3><span class="about-icon">⚖️</span> Persoonlijke calibratie</h3>
            </div>

            <span class="about-more">Lees meer...</span>
        </div>

        <p class="about-preview">
            Bereken je basaalmetabolisme (BMR) <b>en</b> dagelijkse energiebehoefte (TDEE).
        </p>

        <div class="about-extra">
            <p>Stride gebruikt de Mifflin-St Jeor formule om zo de BMR en de TDEE te berekenen.</p>
            <p>Naast de BMR en de TDEE wordt er ook berekent hoeveel calorieën je nodig hebt om af te vallen of aan te komen.</p>
            <p>De berekende gegevens zijn makkelijk neer te zetten als jou persoonlijke doel.</p>
        </div>
    </div>

    <div class="about-section">
        <div class="about-header">
            <div class="about-title">
                <h3><span class="about-icon">📈</span> Progressie bijhouden</h3>
            </div>

            <span class="about-more">Lees meer...</span>
        </div>

        <p class="about-preview">
            Stride houdt jou caloriehistorie van de afgelopen 30 dagen <b>zichtbaar</b> bij.
        </p>

        <div class="about-extra">
            <p>De specifieke producten die je hebt geconsumeerd worden bijgehouden en laten zien bij het progressie tabje.</p>
            <p>Ook wordt er goed bijgehouden of je bepaalde dagen over of onder het doel zat.</p>
            <p>Daarnaast wordt er laten zien wat het gemiddelde calorie-gehalte is van de laatste 30 dagen, om overzichtelijk te houden hoe goed je bezig bent!</p>
        </div>
    </div>

    <div class="about-section" style="border-bottom: none;">
        <div class="about-header">
            <div class="about-title">
                <h3><span class="about-icon">🎯</span> Doelen stellen</h3>
            </div>

            <span class="about-more">Lees meer...</span>
        </div>

        <p class="about-preview">
            Stel een <b>persoonlijk</b> dagelijks caloriedoel en een streefgewicht in.
        </p>

        <div class="about-extra">
            <p>Op basis van de calibratie kan je zelf een persoonlijk doel instellen waar je zelf ook achter staat.</p>
            <p>Dit doel wordt opgeslagen in je profiel, maar kan achteraf altijd nog aangepast worden.</p>
            <p>Ook wordt dit doel gebruikt bij de home pagina om duidelijk te laten zien hoeveel calorieën je nog mag hebben als je je doel aanhoudt.</p>
        </div>
    </div>

</div>

    <div style="text-align:center;margin-top:48px;">
        <a href="regis_pg.php" class="btn-stride" style="font-size:16px;padding:14px 36px;text-decoration:none;">
            Begin nu gratis →
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
            <a href="#">Over Stride</a>
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

<script>
document.querySelectorAll('.about-more').forEach(button => {
    button.addEventListener('click', function () {
        const section = this.closest('.about-section');
        if (!section) return;

        const isActive = section.classList.contains('active');

        document.querySelectorAll('.about-section').forEach(item => {
            item.classList.remove('active');
            item.querySelector('.about-more').textContent = 'Lees meer...';
        });

        if (!isActive) {
            section.classList.add('active');
            this.textContent = 'Minder tonen';
        }
    });
});
</script>
</body>
</html>
