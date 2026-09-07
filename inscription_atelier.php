<?php
require_once 'config.php';

if (!is_logged_in()) {
    header('Location: login.php');
    exit();
}

$atelier_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$message = '';
$erreur = '';
$inscription_confirmee = false;

if ($atelier_id <= 0) {
    $erreur = "Atelier introuvable.";
    $atelier = null;
} else {
    $stmt = $pdo->prepare("SELECT id, titre, date_atelier, heure_debut, heure_fin FROM ateliers WHERE id = :id");
    $stmt->execute([':id' => $atelier_id]);
    $atelier = $stmt->fetch();

    if (!$atelier) {
        $erreur = "Atelier introuvable.";
    }
}

if ($atelier && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $utilisateur_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("SELECT id FROM inscriptions_ateliers WHERE atelier_id = :atelier_id AND utilisateur_id = :utilisateur_id");
    $stmt->execute([
        ':atelier_id' => $atelier_id,
        ':utilisateur_id' => $utilisateur_id
    ]);
    $inscription_existante = $stmt->fetch();

    if ($inscription_existante) {
        $message = "Vous êtes déjà inscrit(e) à cet atelier.";
        $inscription_confirmee = true;
    } else {
        $stmt = $pdo->prepare("INSERT INTO inscriptions_ateliers (atelier_id, utilisateur_id) VALUES (:atelier_id, :utilisateur_id)");
        $stmt->execute([
            ':atelier_id' => $atelier_id,
            ':utilisateur_id' => $utilisateur_id
        ]);

        $message = "Votre inscription a bien été enregistrée.";
        $inscription_confirmee = true;
        log_activity($utilisateur_id, 'Inscription atelier', 'Inscription à l\'atelier : ' . $atelier['titre']);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription atelier - Le Club Famille</title>
    <link rel="stylesheet" type="text/css" href="style.css" media="screen">
    <link rel="stylesheet" type="text/css" href="accessibility-panel.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Nunito:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="accessibility-panel.js"></script>
</head>
<body>
    <header class="header">
        <a href="index.php" class="logo-link" aria-label="<?php echo is_admin() ? 'Club Famille - Admin' : 'Le Club Famille'; ?>">
            <img src="img/logo.png" alt="<?php echo is_admin() ? 'Club Famille - Admin' : 'Le Club Famille'; ?>" class="site-logo">
        </a>
        <div class="header-right">
            <?php if (is_logged_in()): ?>
                <div class="user-info">
                    <span><?php echo htmlspecialchars($_SESSION['prenom']) . ' ' . htmlspecialchars($_SESSION['nom']); ?></span>
                </div>
            <?php endif; ?>
            <div class="user-space">
                <?php if (!is_logged_in()): ?>
                    <a href="admin.php" class="btn-login">Espace Admin</a>
                <?php endif; ?>
                <?php if (is_logged_in()): ?>
                    <a href="logout.php" class="btn-login">Déconnexion</a>
                <?php else: ?>
                    <a href="login.php" class="btn-login">Connexion</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <nav>
        <ul>
            <li><a href="index.php" data-translate-key="nav-about">A propos</a></li>
            <li><a href="calendrier.php" class="active" data-translate-key="nav-calendar">Calendrier</a></li>
            <li><a href="contact.php" data-translate-key="nav-contact">Nous contacter</a></li>
            <?php if (is_admin()): ?>
                <li><a href="admin.php">Admin</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <main>
        <div class="conteneur1" style="margin: 35px auto; text-align: center;">
            <h2 style="text-align:center; margin-top:0;">Inscription à un atelier</h2>

            <?php if ($erreur): ?>
                <p><?php echo htmlspecialchars($erreur); ?></p>
                <p><a href="calendrier.php" class="btn-login" style="display:inline-block; text-decoration:none;">Retour au calendrier</a></p>
            <?php elseif ($inscription_confirmee): ?>
                <h3><?php echo htmlspecialchars($atelier['titre']); ?></h3>
                <p><?php echo htmlspecialchars($message); ?></p>
                <p><a href="calendrier.php" class="btn-login" style="display:inline-block; text-decoration:none;">Retour au calendrier</a></p>
            <?php else: ?>
                <p>Souhaitez-vous confirmer votre inscription à cet atelier ?</p>
                <h3><?php echo htmlspecialchars($atelier['titre']); ?></h3>
                <p>
                    <strong>Date :</strong> <?php echo htmlspecialchars(date('d/m/Y', strtotime($atelier['date_atelier']))); ?><br>
                    <strong>Horaire :</strong> <?php echo htmlspecialchars(substr($atelier['heure_debut'], 0, 5)); ?>
                    <?php if (!empty($atelier['heure_fin'])): ?>
                        - <?php echo htmlspecialchars(substr($atelier['heure_fin'], 0, 5)); ?>
                    <?php endif; ?>
                </p>

                <form method="post">
                    <button type="submit">Confirmer mon inscription</button>
                </form>

                <p><a href="calendrier.php">Annuler et revenir au calendrier</a></p>
            <?php endif; ?>
        </div>
    </main>

    <footer class="footer">
        <div class="footergauche">
            <p><b>Le Club Famille</b></p>
            <p>16 Avenue de Laon</p>
            <p>51100 Reims</p>
            <p>Tél. 03 10 16 10 96</p>
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
