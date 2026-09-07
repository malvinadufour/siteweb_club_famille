<?php
require_once 'config.php';

$message_sent = false;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = clean_input($_POST['nom'] ?? '');
    $email = clean_input($_POST['email'] ?? '');
    $message = clean_input($_POST['message'] ?? '');

    // si l'utilisateur est connecté et n'a pas fourni d'email, utiliser son email de session
    if (is_logged_in() && empty($email) && isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
    }

    // validation
    if (empty($nom) || empty($email) || empty($message)) {
        $error_message = 'Tous les champs sont obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Adresse email invalide.';
    } else {
        // insérer le message dans la base de données
        $stmt = $pdo->prepare("INSERT INTO messages_contact (nom, email, message) VALUES (:nom, :email, :message)");

        if ($stmt->execute([
            ':nom' => $nom,
            ':email' => $email,
            ':message' => $message
        ])) {
            $message_sent = true;
            // réinitialiser les variables
            $nom = $email = $message = '';
        } else {
            $error_message = 'Erreur lors de l\'envoi du message. Veuillez réessayer.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-translate-key="title-contact">Le Club Famille - Nous contacter</title>
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
            <li><a href="calendrier.php" data-translate-key="nav-calendar">Calendrier</a></li>
            <li><a href="contact.php" class="active" data-translate-key="nav-contact">Nous contacter</a></li>
            <?php if (is_admin()): ?>
                <li><a href="admin.php">Admin</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="facade">
        <img src="img/facade.jpg" alt="Façade de l'association Le Club Famille">
    </div>

    <div class="conteneur_visite">
        <h3 data-translate-key="contact-title">Nous contacter</h3>
        <p data-translate-key="contact-location">Nous sommes sur place au <b>16 Avenue de Laon, 51100 Reims</b></p>
        <p data-translate-key="contact-instagram">Sur instagram <b>@clubfamille_lamitie</b></p>
        <p data-translate-key="contact-phone">Par téléphone au <b>03 10 16 10 96</b></p>
    </div>

    <h3 data-translate-key="contact-form-title" style="text-align: center; margin-top: 30px;">Formulaire de contact</h3>

    <?php if ($message_sent): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 10px; margin: 20px auto; max-width: 400px; text-align: center; border: 2px solid #28a745;">
            ✓ Votre message a été envoyé avec succès! Nous vous répondrons bientôt.
        </div>
    <?php elseif (!empty($error_message)): ?>
        <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 10px; margin: 20px auto; max-width: 400px; text-align: center; border: 2px solid #f5c6cb;">
            ⚠ <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <div class="conteneur_contact">
        <p data-translate-key="contact-required-note" style="margin-bottom: 15px; color: #232323; font-size: 14px;">Les champs marqués d’un astérisque (*) sont obligatoires.</p>
        <form method="POST" action="contact.php">
            <label for="nom" data-translate-key="contact-name-label">*Nom</label>
            <input type="text" id="nom" name="nom" placeholder="Votre nom" data-translate-key="contact-name-placeholder" data-translate-attr="placeholder" required value="<?php echo htmlspecialchars($nom ?? ''); ?>">
            <?php if (is_logged_in()): ?>
                <label for="email" data-translate-key="contact-email-label">Email</label>
                <input type="email" id="email" name="email" placeholder="Votre email" data-translate-key="contact-email-placeholder" data-translate-attr="placeholder" value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>">
            <?php else: ?>
                <label for="email" data-translate-key="contact-email-label-required">*Email</label>
                <input type="email" id="email" name="email" placeholder="Votre email" data-translate-key="contact-email-placeholder" data-translate-attr="placeholder" required value="<?php echo htmlspecialchars($email ?? ''); ?>">
            <?php endif; ?>
            <label for="message" data-translate-key="contact-message-label">*Message</label>
            <textarea name="message" id="message" placeholder="Votre message" data-translate-key="contact-message-placeholder" data-translate-attr="placeholder" required><?php echo htmlspecialchars($message ?? ''); ?></textarea>
            <input type="submit" value="Envoyer" data-translate-key="contact-submit" data-translate-attr="value">
        </form>
    </div>

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
