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
