-- Gestion de stock informatique
-- Script de creation et de donnees de test

CREATE DATABASE IF NOT EXISTS gestion_stock CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gestion_stock;

-- Table des roles
CREATE TABLE role (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

-- Table des utilisateurs (administrateur, gestionnaire, employe)
CREATE TABLE utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    id_role INT NOT NULL,
    actif TINYINT(1) DEFAULT 1,
    FOREIGN KEY (id_role) REFERENCES role(id)
);

-- Table des categories de materiel
CREATE TABLE categorie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(100) NOT NULL UNIQUE
);

-- Table du materiel
-- etat : disponible, affecte, panne, hors_service
CREATE TABLE materiel (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_serie VARCHAR(100) NOT NULL UNIQUE,
    nom VARCHAR(150) NOT NULL,
    description TEXT,
    id_categorie INT NOT NULL,
    etat ENUM('disponible', 'affecte', 'panne', 'hors_service') DEFAULT 'disponible',
    quantite INT DEFAULT 1,
    date_entree DATE NOT NULL,
    FOREIGN KEY (id_categorie) REFERENCES categorie(id)
);

-- Table des affectations (historisee)
CREATE TABLE affectation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_materiel INT NOT NULL,
    id_utilisateur INT NOT NULL,
    date_affectation DATE NOT NULL,
    date_retour DATE DEFAULT NULL,
    commentaire TEXT,
    FOREIGN KEY (id_materiel) REFERENCES materiel(id),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id)
);

-- Table des mouvements de stock (entree / sortie)
CREATE TABLE mouvement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_materiel INT NOT NULL,
    type_mouvement ENUM('entree', 'sortie') NOT NULL,
    quantite INT NOT NULL,
    date_mouvement DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_utilisateur INT NOT NULL,
    motif TEXT,
    FOREIGN KEY (id_materiel) REFERENCES materiel(id),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id)
);

-- Table des pannes declarees
CREATE TABLE panne (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_materiel INT NOT NULL,
    id_declarant INT NOT NULL,
    date_declaration DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    description TEXT NOT NULL,
    statut ENUM('ouverte', 'en_cours', 'resolue') DEFAULT 'ouverte',
    FOREIGN KEY (id_materiel) REFERENCES materiel(id),
    FOREIGN KEY (id_declarant) REFERENCES utilisateur(id)
);

-- -------------------------------------------------------
-- Donnees de test
-- -------------------------------------------------------

INSERT INTO role (libelle) VALUES ('administrateur'), ('gestionnaire'), ('employe');

-- Mots de passe : admin123, gest123, emp123 (haches en SHA-256 via PHP password_hash)
-- On insere les hash directement pour les tests
INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, id_role) VALUES
('Dupont', 'Alice',  'admin@stock.fr',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
('Martin', 'Bob',   'gest@stock.fr',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2),
('Leroy',  'Claire','emp@stock.fr',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3);

-- Note : le hash ci-dessus correspond au mot de passe "password"
-- Pour changer les mots de passe, lancer : php -r "echo password_hash('votre_mdp', PASSWORD_DEFAULT);"

INSERT INTO categorie (libelle) VALUES
('Ordinateur portable'),
('Ordinateur fixe'),
('Ecran'),
('Clavier / Souris'),
('Imprimante'),
('Serveur');

INSERT INTO materiel (numero_serie, nom, description, id_categorie, etat, quantite, date_entree) VALUES
('SN-001-LP', 'Dell Latitude 5520', '15 pouces, Intel i5, 16 Go RAM', 1, 'disponible', 1, '2024-01-10'),
('SN-002-LP', 'HP EliteBook 840',   '14 pouces, Intel i7, 8 Go RAM',  1, 'affecte',    1, '2024-02-15'),
('SN-003-PC', 'Lenovo ThinkCentre', 'Tour, Intel i5, 8 Go RAM',       2, 'disponible', 1, '2024-03-01'),
('SN-004-EC', 'Samsung 27 pouces',  'Ecran Full HD',                  3, 'disponible', 2, '2024-03-05'),
('SN-005-IM', 'HP LaserJet Pro',    'Imprimante laser monochrome',    5, 'panne',      1, '2023-11-20');

-- Affectation de SN-002-LP a l employe Claire
INSERT INTO affectation (id_materiel, id_utilisateur, date_affectation, commentaire) VALUES
(2, 3, '2024-04-01', 'Attribution pour le projet client');

-- Mouvements
INSERT INTO mouvement (id_materiel, type_mouvement, quantite, id_utilisateur, motif) VALUES
(1, 'entree', 1, 2, 'Achat initial'),
(2, 'entree', 1, 2, 'Achat initial'),
(3, 'entree', 1, 2, 'Achat initial'),
(4, 'entree', 2, 2, 'Achat initial'),
(5, 'entree', 1, 2, 'Achat initial');

-- Panne declaree sur l imprimante
INSERT INTO panne (id_materiel, id_declarant, description, statut) VALUES
(5, 3, 'L imprimante ne s allume plus apres une coupure de courant.', 'ouverte');
