CREATE DATABASE IF NOT EXISTS matcha;

USE matcha;

CREATE TABLE IF NOT EXISTS User (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    pw VARCHAR(255) NOT NULL,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    age INT NOT NULL,
    panier_id INT
);

CREATE TABLE IF NOT EXISTS Admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    num_employe INT NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES User(id)
);

CREATE TABLE IF NOT EXISTS Client (
    id INT AUTO_INCREMENT PRIMARY KEY,
    age INT NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES User(id)
);

CREATE TABLE IF NOT EXISTS Adresse (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero INT NOT NULL,
    apartement VARCHAR(255),
    rue VARCHAR(255) NOT NULL,
    ville VARCHAR(255) NOT NULL,
    codePostal VARCHAR(20) NOT NULL,
    province VARCHAR(255) NOT NULL,
    pays VARCHAR(255) NOT NULL,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES User(id)
);

CREATE TABLE IF NOT EXISTS Categorie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categorie VARCHAR(255) NOT NULL,
    image VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS Sport (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    categorie_id INT,
    FOREIGN KEY (categorie_id) REFERENCES Categorie(id)
);

CREATE TABLE IF NOT EXISTS Equipement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    sport_id INT,
    FOREIGN KEY (sport_id) REFERENCES Sport(id)
);

CREATE TABLE IF NOT EXISTS Panier (
    id INT AUTO_INCREMENT PRIMARY KEY,
    User_id INT,
    FOREIGN KEY (user_id) REFERENCES User(id)
);

CREATE TABLE IF NOT EXISTS PanierEquipement (
    panier_id INT,
    equipement_id INT,
    quantite INT NOT NULL,
    PRIMARY KEY (panier_id, equipement_id),
    FOREIGN KEY (panier_id) REFERENCES Panier(id),
    FOREIGN KEY (equipement_id) REFERENCES Equipement(id)
);

CREATE TABLE IF NOT EXISTS Commande (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    adresse_id INT,
    date_commande DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES User(id),
    FOREIGN KEY (adresse_id) REFERENCES Adresse(id)
);

CREATE TABLE IF NOT EXISTS CommandeEquipement (
    commande_id INT,
    equipement_nom VARCHAR(255) NOT NULL,
    equipement_prix DECIMAL(10,2) NOT NULL,
    quantite INT NOT NULL,
    PRIMARY KEY (commande_id, equipement_nom),
    FOREIGN KEY (commande_id) REFERENCES Commande(id)
);

CREATE TABLE IF NOT EXISTS Messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content TEXT NOT NULL,
    date_message DATETIME DEFAULT CURRENT_TIMESTAMP,
    from_id INT,
    to_id INT,
    FOREIGN KEY (from_id) REFERENCES User(id),
    FOREIGN KEY (to_id) REFERENCES User(id)
);

CREATE TABLE IF NOT EXISTS UserAdresse (
    user_id INT,
    adresse_id INT,
    PRIMARY KEY (user_id, adresse_id),
    FOREIGN KEY (user_id) REFERENCES User(id),
    FOREIGN KEY (adresse_id) REFERENCES Adresse(id)
);

CREATE TABLE IF NOT EXISTS UserMessage (
    user_id INT,
    message_id INT,
    PRIMARY KEY (user_id, message_id),
    FOREIGN KEY (user_id) REFERENCES User(id),
    FOREIGN KEY (message_id) REFERENCES Messages(id)
);
