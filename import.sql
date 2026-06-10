/*
    Door de wachtwoord hash kan er geen test account worden toegevoegd via de import
    Maak een test account aan via de registratie pagina
    Gebruik de volgende gegevens:
    gebruikersnaam: tester
    e-mail: test@gmail.com
    wachtwoord: wachtwoord123
*/
CREATE DATABASE IF NOT EXISTS stride;

USE stride;

CREATE TABLE gebruikers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE gebruiker_profiel (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gebruiker_id INT NOT NULL UNIQUE,
    leeftijd INT,
    lengte INT,
    gewicht DECIMAL(5,2),
    geslacht ENUM('man','vrouw'),
    activiteit ENUM('sedentair','licht','matig','actief','zeer_actief'),
    calorie_doel INT,
    gewicht_doel DECIMAL(5,2),
    FOREIGN KEY (gebruiker_id) REFERENCES gebruikers(id) ON DELETE CASCADE
);

CREATE TABLE ingredienten (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(100) NOT NULL,
    calorieen_per_100g DECIMAL(6,2) NOT NULL,
    categorie VARCHAR(50)
);

CREATE TABLE calorie_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gebruiker_id INT NOT NULL,
    datum DATE NOT NULL,
    totaal_calorieen INT NOT NULL DEFAULT 0,
    aangemaakt_op DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_user_day (gebruiker_id, datum),
    FOREIGN KEY (gebruiker_id) REFERENCES gebruikers(id) ON DELETE CASCADE
);

CREATE TABLE calorie_log_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    log_id INT NOT NULL,
    ingredient_id INT NOT NULL,
    hoeveelheid_gram DECIMAL(6,2) NOT NULL,
    calorieen INT NOT NULL,
    FOREIGN KEY (log_id) REFERENCES calorie_log(id) ON DELETE CASCADE,
    FOREIGN KEY (ingredient_id) REFERENCES ingredienten(id)
);

INSERT INTO ingredienten (naam, categorie, calorieen_per_100g) VALUES
('Lays Naturel chips', 'Snack', 536),
('Lays Paprika chips', 'Snack', 535),
('Pringles Original', 'Snack', 536),
('Pringles Sour Cream & Onion', 'Snack', 520),
('Doritos Nacho Cheese', 'Snack', 500),
('Bugles', 'Snack', 520),
('Cheetos', 'Snack', 536),
('Croky chips naturel', 'Snack', 530),
('Mars reep', 'Snoep', 450),
('Snickers', 'Snoep', 480),
('Twix', 'Snoep', 495),
('KitKat', 'Snoep', 518),
('Milka melkchocolade', 'Snoep', 535),
('Tonys Chocolonely melk', 'Snoep', 540),
('Haribo gummibeertjes', 'Snoep', 343),
('Skittles', 'Snoep', 405),
('M&Ms', 'Snoep', 505),
('Drop (gemiddeld)', 'Snoep', 360),
('Coca Cola', 'Drinken', 42),
('Coca Cola Zero', 'Drinken', 1),
('Fanta Orange', 'Drinken', 38),
('Sprite', 'Drinken', 37),
('Ice Tea Lipton', 'Drinken', 30),
('Red Bull', 'Drinken', 45),
('Monster Energy', 'Drinken', 46),
('Appelsap (pak)', 'Drinken', 46),
('Sinaasappelsap', 'Drinken', 45),
('Dubbelfrisss', 'Drinken', 25),
('Wit brood', 'Zetmeel', 265),
('Volkoren brood', 'Zetmeel', 247),
('Croissant', 'Zetmeel', 406),
('Beschuit', 'Zetmeel', 410),
('Nutella', 'Beleg', 539),
('Jam aardbei', 'Beleg', 250),
('Hagelslag melk', 'Beleg', 460),
('Pindakaas', 'Beleg', 588),
('Kipfilet', 'Eiwit', 165),
('Kip nuggets', 'Eiwit', 290),
('Salami', 'Eiwit', 400),
('Ham', 'Eiwit', 145),
('Zalm', 'Eiwit', 208),
('Tonijn (blik in water)', 'Eiwit', 116),
('Ei', 'Eiwit', 155),
('Melk halfvol', 'Zuivel', 50),
('Melk vol', 'Zuivel', 64),
('Yoghurt naturel', 'Zuivel', 59),
('Griekse yoghurt', 'Zuivel', 120),
('Goudse kaas 48+', 'Zuivel', 356),
('Roomkaas', 'Zuivel', 342),
('Pizza Margherita', 'Fastfood', 266),
('Pizza Pepperoni', 'Fastfood', 280),
('Friet', 'Fastfood', 312),
('Kapsalon', 'Fastfood', 750),
('Bitterballen', 'Fastfood', 250),
('Frikandel', 'Fastfood', 270),
('Kroket', 'Fastfood', 260),
('Appel', 'Fruit', 52),
('Banaan', 'Fruit', 89),
('Aardbei', 'Fruit', 32),
('Blauwe bessen', 'Fruit', 57),
('Tomaat', 'Fruit', 18),
('Komkommer', 'Groente', 15),
('Wortel', 'Groente', 41),
('Spinazie', 'Groente', 23),
('Avocado', 'Fruit', 160);