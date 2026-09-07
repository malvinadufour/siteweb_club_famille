<?php
require_once 'config.php';

if (!is_logged_in()) {
    header('Location: login.php?from=admin');
    exit();
}

if (!is_admin()) {
    header('Location: index.php');
    exit();
}

$stmt = $pdo->query("SELECT a.id, a.titre, a.date_atelier, a.heure_debut, a.heure_fin,
                           u.nom, u.prenom, u.email, i.date_inscription
                    FROM ateliers a
                    LEFT JOIN inscriptions_ateliers i ON i.atelier_id = a.id
                    LEFT JOIN utilisateurs u ON u.id = i.utilisateur_id
                    ORDER BY a.date_atelier, a.heure_debut, a.titre, u.nom, u.prenom");
$lignes = $stmt->fetchAll();

$ateliers = [];
foreach ($lignes as $ligne) {
    $id = $ligne['id'];
    if (!isset($ateliers[$id])) {
        $ateliers[$id] = [
            'titre' => $ligne['titre'],
            'date_atelier' => $ligne['date_atelier'],
            'heure_debut' => $ligne['heure_debut'],
            'heure_fin' => $ligne['heure_fin'],
            'inscrits' => []
        ];
    }

    if (!empty($ligne['email'])) {
        $ateliers[$id]['inscrits'][] = $ligne;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscriptions ateliers - Club Famille</title>
    <link rel="stylesheet" type="text/css" href="style.css" media="screen">
    <link rel="stylesheet" type="text/css" href="accessibility-panel.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Nunito:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="accessibility-panel.js"></script>
    <style>
        .admin-page { width: 95%; max-width: 1200px; margin: 30px auto; }
        .admin-table { width: 100%; border-collapse: collapse; background: #ffffff; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border-radius: 20px; overflow: hidden; margin-bottom: 28px; }
        .admin-table th, .admin-table td { padding: 14px 16px; border-bottom: 1px solid rgba(0,0,0,0.08); text-align: left; }
        .admin-table th { background: #f4e9e9; font-weight: 700; }
    </style>
</head>
<body>
    <header class="header">
        <a href="index.php" class="logo-link" aria-label="Club Famille - Admin">
            <img src="img/logo.png" alt="Club Famille - Admin" class="site-logo">
        </a>
        <div class="header-right">
            <div class="user-info"><span><?php echo htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']); ?></span></div>
            <div class="user-space"><a href="logout.php" class="btn-login">Déconnexion</a></div>
        </div>
    </header>

    <nav>
        <ul>
            <li><a href="index.php" data-translate-key="nav-about">A propos</a></li>
            <li><a href="calendrier.php" data-translate-key="nav-calendar">Calendrier</a></li>
            <li><a href="contact.php" data-translate-key="nav-contact">Nous contacter</a></li>
            <li><a href="admin.php">Admin</a></li>
        </ul>
    </nav>

    <main class="admin-page">
        <h2>Inscriptions aux ateliers</h2>

        <?php if (empty($ateliers)): ?>
            <p>Aucun atelier trouvé.</p>
        <?php endif; ?>

        <?php foreach ($ateliers as $atelier): ?>
            <h3 style="text-align:left;">
                <?php echo htmlspecialchars($atelier['titre']); ?> —
                <?php echo htmlspecialchars(date('d/m/Y', strtotime($atelier['date_atelier']))); ?>
                à <?php echo htmlspecialchars(substr($atelier['heure_debut'], 0, 5)); ?>
            </h3>

            <?php if (empty($atelier['inscrits'])): ?>
                <p>Aucun inscrit pour cet atelier.</p>
            <?php else: ?>
                <table class="admin-table">
                    <thead>
                        <tr><th>Nom</th><th>Email</th><th>Date d'inscription</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($atelier['inscrits'] as $inscrit): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($inscrit['prenom'] . ' ' . $inscrit['nom']); ?></td>
                                <td><?php echo htmlspecialchars($inscrit['email']); ?></td>
                                <td><?php echo htmlspecialchars($inscrit['date_inscription']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php endforeach; ?>
    </main>

    <footer class="footer">
        <div class="footergauche">
            <p><b>Le Club Famille</b></p><p>16 Avenue de Laon</p><p>51100 Reims</p><p>Tél. 03 10 16 10 96</p>
        </div>
        <div class="footerdroit">
            <p><b data-translate-key="footer-links">Liens</b></p>
            <p><a href="index.php" data-translate-key="footer-about">A propos de nous</a></p>
            <p><a href="calendrier.php" data-translate-key="footer-calendar">Calendrier</a></p>
            <p><a href="contact.php" data-translate-key="footer-contact">Nous contacter</a></p>
        </div>
    </footer>
</body>
</html>
