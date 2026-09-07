<?php
require_once 'config.php';

// vérifier l'accès : seul admin@clubfamille.fr est autorisé
if (!is_logged_in()) {
    // non connecté : redirection vers login avec marqueur admin
    header('Location: login.php?from=admin');
    exit();
}

// vérifier que l'email est exactement admin@clubfamille.fr
$email = $_SESSION['email'] ?? '';
if ($email !== 'admin@clubfamille.fr') {
    // utilisateur connecté mais pas admin autorisé
    $access_denied = true;
    $utilisateurs = [];
    $messages = [];
    $activites = [];
    $colonne_date_utilisateur = false;
    $colonne_date_message = false;
    $colonne_date_activite = false;
} else {
    $access_denied = false;

    function column_exists($pdo, $table, $column) {
        try {
            $stmt = $pdo->prepare("SHOW COLUMNS FROM `$table` LIKE :column");
            $stmt->execute([':column' => $column]);
            return $stmt->fetch() !== false;
        } catch (PDOException $e) {
            return false;
        }
    }

    // utilisateurs
    $colonne_date_utilisateur = column_exists($pdo, 'utilisateurs', 'date_creation');
    $userColumns = 'id, nom, prenom, email';
    if ($colonne_date_utilisateur) {
        $userColumns .= ', date_creation';
    }
    try {
        $stmt = $pdo->query("SELECT $userColumns FROM utilisateurs ORDER BY nom, prenom");
        $utilisateurs = $stmt->fetchAll();
    } catch (PDOException $e) {
        $utilisateurs = [];
    }

    // messages de contact
    $colonne_date_message = column_exists($pdo, 'messages_contact', 'date_creation');
    $contactColumns = 'id, nom, email, message';
    if ($colonne_date_message) {
        $contactColumns .= ', date_creation';
    }
    try {
        $stmt = $pdo->query("SELECT $contactColumns FROM messages_contact ORDER BY id DESC");
        $messages = $stmt->fetchAll();
    } catch (PDOException $e) {
        $messages = [];
    }

    // inscriptions aux ateliers
    try {
        $stmt = $pdo->query("
            SELECT 
                ia.id,
                a.titre AS atelier,
                a.date_atelier,
                a.heure_debut,
                a.heure_fin,
                u.nom,
                u.prenom,
                u.email,
                ia.date_inscription
            FROM inscriptions_ateliers ia
            JOIN ateliers a ON ia.atelier_id = a.id
            JOIN utilisateurs u ON ia.utilisateur_id = u.id
            ORDER BY a.date_atelier, a.heure_debut, u.nom
        ");
        $inscriptions_ateliers = $stmt->fetchAll();
    } catch (PDOException $e) {
        $inscriptions_ateliers = [];
    }

    // journaux d'activité
    // nombre d'inscrits par atelier
    try {
        $stmt = $pdo->query("
            SELECT
                a.id,
                a.titre,
                a.date_atelier,
                a.heure_debut,
                a.heure_fin,
                COUNT(ia.id) AS nombre_inscrits
            FROM ateliers a
            LEFT JOIN inscriptions_ateliers ia ON ia.atelier_id = a.id
            GROUP BY a.id, a.titre, a.date_atelier, a.heure_debut, a.heure_fin
            ORDER BY a.date_atelier, a.heure_debut
        ");

        $nombre_inscrits_ateliers = $stmt->fetchAll();

    } catch (PDOException $e) {
        $nombre_inscrits_ateliers = [];
    }


    $colonne_date_activite = column_exists($pdo, 'journaux_activite', 'date_creation');
    $activityColumns = 'a.id, a.utilisateur_id, a.action, a.description';
    if ($colonne_date_activite) {
        $activityColumns .= ', a.date_creation';
    }
    try {
        $stmt = $pdo->query("SELECT $activityColumns, u.nom AS user_nom, u.prenom AS user_prenom FROM journaux_activite a LEFT JOIN utilisateurs u ON a.utilisateur_id = u.id ORDER BY a.id DESC");
        $activites = $stmt->fetchAll();
    } catch (PDOException $e) {
        $activites = [];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Club Famille</title>
    <link rel="stylesheet" type="text/css" href="style.css" media="screen">
    <link rel="stylesheet" type="text/css" href="accessibility-panel.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Nunito:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="accessibility-panel.js"></script>
    <style>
        .admin-page { width: 95%; max-width: 1200px; margin: 30px auto; }
        .admin-section { margin-bottom: 40px; }
        .admin-section h2 { margin-bottom: 20px; font-size: 26px; color: #232323; }
        .admin-table { width: 100%; border-collapse: collapse; background: #ffffff; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border-radius: 20px; overflow: hidden; }
        .admin-table th, .admin-table td { padding: 14px 16px; border-bottom: 1px solid rgba(0,0,0,0.08); text-align: left; }
        .admin-table th { background: #f4e9e9; font-weight: 700; }
        .admin-table tbody tr:hover { background: #f9f2ff; }
        .admin-summary { display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 24px; }
        .admin-card { flex: 1; min-width: 220px; padding: 18px 20px; border-radius: 22px; background: linear-gradient(135deg, #fff7e8 0%, #f8f0ff 100%); border: 1px solid rgba(35,35,35,0.08); box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .admin-card strong { display: block; margin-bottom: 10px; font-size: 18px; }
    </style>
</head>
<body>
    <header class="header">
        <a href="index.php" class="logo-link" aria-label="Club Famille - Admin">
            <img src="img/logo.png" alt="Club Famille - Admin" class="site-logo">
        </a>
        <div class="header-right">
            <div class="user-info">
                <span><?php echo htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']); ?></span>
            </div>
            <div class="user-space">
                <a href="logout.php" class="btn-login">Déconnexion</a>
            </div>
        </div>
    </header>

    <nav>
        <ul>
            <li><a href="index.php" data-translate-key="nav-about">A propos</a></li>
            <li><a href="calendrier.php" data-translate-key="nav-calendar">Calendrier</a></li>
            <li><a href="contact.php" data-translate-key="nav-contact">Nous contacter</a></li>
            <li><a href="admin.php" class="active">Admin</a></li>
        </ul>
    </nav>

    <?php if ($access_denied): ?>
        <div style="background-color: #f8d7da; color: #721c24; padding: 30px; border-radius: 10px; margin: 40px auto; max-width: 600px; text-align: center; border: 2px solid #f5c6cb;">
            <h2 style="margin: 0 0 15px 0;">Accès Refusé</h2>
            <p style="margin: 0 0 15px 0;">Vous ne disposez pas des droits d'accès à l'espace administrateur.</p>
            <p style="margin: 0 0 20px 0; font-size: 14px;">Seul admin@clubfamille.fr est autorisé à accéder à cette zone.</p>
            <a href="index.php" style="color: #721c24; text-decoration: underline; font-weight: 600;">← Retour à l'accueil</a>
        </div>
    <?php else: ?>

    <div class="admin-page">
        <div class="admin-summary">
            <div class="admin-card"><strong>Membres inscrits</strong><span><?php echo count($utilisateurs); ?></span></div>
            <div class="admin-card"><strong>Messages</strong><span><?php echo count($messages); ?></span></div>
            <div class="admin-card"><strong>Activités enregistrées</strong><span><?php echo count($activites); ?></span></div>
        </div>

        <div class="admin-section">
            <h2>Utilisateurs inscrits</h2>
            <?php if (!empty($utilisateurs)): ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th><th>Nom</th><th>Email</th>
                            <?php if ($colonne_date_utilisateur): ?><th>Inscrit le</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($utilisateurs as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['id']); ?></td>
                                <td><?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <?php if (isset($user['date_creation'])): ?><td><?php echo htmlspecialchars($user['date_creation']); ?></td><?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Aucun utilisateur trouvé.</p>
            <?php endif; ?>
        </div>



        <div class="admin-section">
            <h2>Inscriptions aux ateliers</h2>
            <?php if (!empty($inscriptions_ateliers)): ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Atelier</th>
                            <th>Date</th>
                            <th>Horaire</th>
                            <th>Participant</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inscriptions_ateliers as $inscription): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($inscription['atelier']); ?></td>

                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($inscription['date_atelier']))); ?></td>

                                <td>
                                    <?php echo htmlspecialchars(substr($inscription['heure_debut'], 0, 5)); ?>
                                    <?php if (!empty($inscription['heure_fin'])): ?>
                                        <?php echo htmlspecialchars(substr($inscription['heure_fin'], 0, 5)); ?>
                                    <?php endif; ?>
                                </td>

                                <td><?php echo htmlspecialchars($inscription['prenom'] . ' ' . $inscription['nom']); ?></td>

                                <td><?php echo htmlspecialchars($inscription['email']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Aucune inscription aux ateliers.</p>
            <?php endif; ?>
        </div>
        
        <div class="admin-section">
            <h2>Nombre d'inscrits par atelier</h2>

            <?php if (!empty($nombre_inscrits_ateliers)): ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Atelier</th>
                            <th>Date</th>
                            <th>Horaire</th>
                            <th>Nombre d'inscrits</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($nombre_inscrits_ateliers as $atelier): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($atelier['titre']); ?></td>
                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($atelier['date_atelier']))); ?></td>
                                <td>
                                    <?php echo htmlspecialchars(substr($atelier['heure_debut'], 0, 5)); ?>
                                    <?php if (!empty($atelier['heure_fin'])): ?>
                                        <?php echo htmlspecialchars(substr($atelier['heure_fin'], 0, 5)); ?>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($atelier['nombre_inscrits']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Aucun atelier trouvé.</p>
            <?php endif; ?>
        </div>
            
            
            <h2>Messages du formulaire</h2>
            <?php if (!empty($messages)): ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th><th>Nom</th><th>Email</th><th>Message</th>
                            <?php if ($colonne_date_message): ?><th>Reçu le</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $message): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($message['id']); ?></td>
                                <td><?php echo htmlspecialchars($message['nom']); ?></td>
                                <td><?php echo htmlspecialchars($message['email']); ?></td>
                                <td><?php echo nl2br(htmlspecialchars($message['message'])); ?></td>
                                <?php if (isset($message['date_creation'])): ?><td><?php echo htmlspecialchars($message['date_creation']); ?></td><?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Aucun message trouvé.</p>
            <?php endif; ?>
        </div>

        <div class="admin-section">
            <h2>Activités récentes</h2>
            <?php if (!empty($activites)): ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th><th>Utilisateur</th><th>Action</th><th>Description</th>
                            <?php if ($colonne_date_activite): ?><th>Date</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($activites as $log): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($log['id']); ?></td>
                                <td><?php echo htmlspecialchars(trim(($log['user_prenom'] ?? '') . ' ' . ($log['user_nom'] ?? '')) ?: 'Utilisateur #'.htmlspecialchars($log['utilisateur_id'])); ?></td>
                                <td><?php echo htmlspecialchars($log['action']); ?></td>
                                <td><?php echo htmlspecialchars($log['description']); ?></td>
                                <?php if (isset($log['date_creation'])): ?><td><?php echo htmlspecialchars($log['date_creation']); ?></td><?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Aucune activité trouvée.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</body>
</html>
