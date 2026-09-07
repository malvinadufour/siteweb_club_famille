<?php
// configuration de la base de données avec PDO

$db_hote = 'localhost';
$db_utilisateur = 'malvina';
$db_mot_de_passe = 'motdepasse';
$db_nom = 'nomdelabase';

try {
    $pdo = new PDO(
        "mysql:host=$db_hote;dbname=$db_nom;charset=utf8mb4",
        $db_utilisateur,
        $db_mot_de_passe,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// démarrer la session si pas déjà démarrée
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// fonction pour nettoyer les entrées
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// liste des administrateurs autorisés
const ADMIN_EMAILS = [
    'admin@clubfamille.fr'
];

// fonction pour vérifier si l'utilisateur est connecté
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// fonction pour vérifier si l'utilisateur est administrateur
function is_admin() {
    $email = $_SESSION['email'] ?? '';
    return (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) || in_array($email, ADMIN_EMAILS, true);
}

// fonction pour obtenir l'utilisateur courant
function get_logged_user() {
    if (is_logged_in()) {
        return $_SESSION['username'];
    }
    return null;
}

// fonction pour enregistrer une activité
function log_activity($utilisateur_id, $action, $description = '') {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO journaux_activite (utilisateur_id, action, description) VALUES (:utilisateur_id, :action, :description)");
        $stmt->execute([
            ':utilisateur_id' => $utilisateur_id,
            ':action' => $action,
            ':description' => $description
        ]);
    } catch (PDOException $e) {
        return;
    }
}
?>
