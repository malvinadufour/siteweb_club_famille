<?php
require_once 'config.php';

// enregistrer l'activité avant de déconnecter
if (is_logged_in()) {
    log_activity($_SESSION['user_id'], 'Déconnexion', 'Utilisateur déconnecté');
}

// détruire la session
session_destroy();

// rediriger vers la page d'accueil
header('Location: index.php');
exit();
?>
