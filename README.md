# Stride

Een gratis, persoonlijke calorie- en voedingstracker. Gebruikers loggen wat ze eten, berekenen hun dagelijkse energiebehoefte en volgen hun voortgang over de afgelopen 30 dagen. Gebouwd als schoolproject door Isabelle Gits.

## Stack

- PHP (PDO + MySQL)
- MySQL / MariaDB
- Bootstrap 4 (navbar, modals, layout)
- Vanilla JavaScript (calculator, calorieteller)
- HTML / CSS

## Functies

- **Accounts**: registreren en inloggen met gehashte wachtwoorden (`password_hash` / `password_verify`) en sessies.
- **Calorieteller**: producten uit de database kiezen, grammen invoeren en de dag-totalen opslaan. Eigen producten kunnen worden toegevoegd.
- **Calibratie**: BMR en TDEE berekenen via de Mifflin-St Jeor-formule, plus een caloriedoel en streefgewicht instellen.
- **Progressie**: overzicht van de laatste 30 gelogde dagen, met status (boven/onder doel) en gegeten producten.
- **Home**: dagdashboard met het totaal van vandaag tegenover het doel.
- **Profiel**: lichaamsgegevens bekijken en account-/wachtwoordgegevens aanpassen.

## Bestanden

| Bestand | Functie |
|---|---|
| `config.php` | Databaseverbinding (PDO) |
| `intro_pg.php` | Landingspagina |
| `overstride_pg.php` | Uitleg over het product |
| `overons_pg.php` | Over de maker |
| `regis_pg.php` / `inlog_pg.php` | Registreren / inloggen |
| `home_pg.php` | Dashboard van vandaag |
| `teller_pg.php` | Calorieteller + nieuw product |
| `toevoeg.php` | Verwerkt het toevoegen van een product |
| `calibr_pg.php` | BMR/TDEE-calculator en doelen opslaan |
| `progres_pg.php` | Geschiedenis van 30 dagen |
| `profiel_pg.php` | Profiel- en accountbeheer |
| `uitlog.php` | Uitloggen (sessie vernietigen) |
| `import.sql` | Databaseschema + ingrediënten-seeddata |
| `style.css` | Opmaak |

## Database

`import.sql` maakt de database `stride` met vijf tabellen: `gebruikers`, `gebruiker_profiel`, `ingredienten`, `calorie_log` en `calorie_log_items` (met foreign keys en `ON DELETE CASCADE`). De ingrediëntentabel wordt gevuld met testdata.

## Installatie (XAMPP)

1. Start **Apache** en **MySQL** in XAMPP.
2. Importeer de database: open phpMyAdmin (`localhost/phpmyadmin`) → tab **Importeren** → kies `import.sql` → **Uitvoeren**.
3. Plaats de projectbestanden in `C:\xampp\htdocs\Stride`.
4. Open `http://localhost/Stride/intro_pg.php`.

Er wordt geen testaccount geïmporteerd (door de wachtwoord-hash). Maak er een aan via de registratiepagina, bijvoorbeeld:

```
gebruikersnaam: tester
e-mail:         test@gmail.com
wachtwoord:     wachtwoord123
```

## Repository

https://github.com/IBelleGits/stride.git
