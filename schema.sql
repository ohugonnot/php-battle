CREATE DATABASE IF NOT EXISTS battle;
USE battle;

CREATE TABLE IF NOT EXISTS fighters (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    name     VARCHAR(100) NOT NULL,
    sante    INT          NOT NULL,
    attaque  INT          NOT NULL,
    mana     INT          NOT NULL
);

CREATE TABLE IF NOT EXISTS fights (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    fighter1  INT          NOT NULL,
    fighter2  INT          NOT NULL,
    winner    INT          DEFAULT NULL,
    logs      JSON         DEFAULT NULL,
    FOREIGN KEY (fighter1) REFERENCES fighters(id),
    FOREIGN KEY (fighter2) REFERENCES fighters(id),
    FOREIGN KEY (winner)   REFERENCES fighters(id)
);

-- Fixtures
INSERT INTO fighters (name, sante, attaque, mana) VALUES
    ('Aragorn',  120, 35, 80),
    ('Sauron',   150, 45, 60),
    ('Legolas',   90, 50, 100),
    ('Gandalf',  100, 40, 150),
    ('Gimli',    130, 42, 40),
    ('Nazgûl',   110, 38, 90);

INSERT INTO fights (fighter1, fighter2, winner, logs) VALUES
    (1, 2, 1, '["Aragorn attaque Sauron et lui inflige 35 dégâts.", "Sauron attaque Aragorn et lui inflige 45 dégâts.", "Aragorn attaque Sauron et lui inflige 35 dégâts.", "Aragorn a tué Sauron, c est la fin de la partie"]'),
    (3, 4, 4, '["Legolas attaque Gandalf et lui inflige 50 dégâts.", "Gandalf se soigne et restaure 25 points de vie.", "Legolas attaque Gandalf et lui inflige 50 dégâts.", "Gandalf attaque Legolas et lui inflige 40 dégâts.", "Gandalf a tué Legolas, c est la fin de la partie"]'),
    (5, 6, 5, '["Gimli attaque Nazgûl et lui inflige 42 dégâts.", "Nazgûl attaque Gimli et lui inflige 38 dégâts.", "Gimli attaque Nazgûl et lui inflige 42 dégâts.", "Gimli a tué Nazgûl, c est la fin de la partie"]'),
    (1, 3, 3, '["Aragorn attaque Legolas et lui inflige 35 dégâts.", "Legolas attaque Aragorn et lui inflige 50 dégâts.", "Legolas a tué Aragorn, c est la fin de la partie"]'),
    (2, 4, 2, '["Sauron attaque Gandalf et lui inflige 45 dégâts.", "Gandalf se soigne et restaure 25 points de vie.", "Sauron attaque Gandalf et lui inflige 45 dégâts.", "Sauron a tué Gandalf, c est la fin de la partie"]'),
    (4, 1, 4, '["Gandalf attaque Aragorn et lui inflige 40 dégâts.", "Aragorn attaque Gandalf et lui inflige 35 dégâts.", "Gandalf attaque Aragorn et lui inflige 40 dégâts.", "Gandalf a tué Aragorn, c est la fin de la partie"]');
