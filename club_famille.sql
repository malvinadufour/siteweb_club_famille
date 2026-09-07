/* base de données pour Le Club Famille sur ma base de données p27_malvina */
/* table des utilisateurs pour la connexion */
CREATE TABLE utilisateurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

/* table des messages de contact */
CREATE TABLE messages_contact (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

/* table des ateliers du calendrier */
CREATE TABLE ateliers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    date_atelier DATE NOT NULL,
    heure_debut TIME NOT NULL,
    heure_fin TIME,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

/* table des inscriptions aux ateliers */
CREATE TABLE inscriptions_ateliers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    atelier_id INT NOT NULL,
    utilisateur_id INT NOT NULL,
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (atelier_id, utilisateur_id),
    FOREIGN KEY (atelier_id) REFERENCES ateliers(id) ON DELETE CASCADE,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

/* table pour voir l'activité des utilisateurs (connexion/déconnexion/inscriptions) */
CREATE TABLE journaux_activite (
    id INT PRIMARY KEY AUTO_INCREMENT,
    utilisateur_id INT,
    action VARCHAR(100),
    description TEXT,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE SET NULL
);

/* index */
CREATE INDEX idx_email ON utilisateurs(email);
CREATE INDEX idx_contact_email ON messages_contact(email);
CREATE INDEX idx_journal_utilisateur ON journaux_activite(utilisateur_id);
CREATE INDEX idx_atelier_date ON ateliers(date_atelier);
CREATE INDEX idx_inscription_atelier ON inscriptions_ateliers(atelier_id);
CREATE INDEX idx_inscription_utilisateur ON inscriptions_ateliers(utilisateur_id);
