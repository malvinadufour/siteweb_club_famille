<?php
require_once 'config.php';

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = clean_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $nom = clean_input($_POST['nom'] ?? '');
    $prenom = clean_input($_POST['prenom'] ?? '');

    // validation
    if (empty($email) || empty($password)) {
        $error_message = 'Les champs obligatoires doivent être remplis.';
    } elseif (strlen($password) < 6) {
        $error_message = 'Le mot de passe doit contenir au moins 6 caractères.';
    } elseif ($password !== $password_confirm) {
        $error_message = 'Les mots de passe ne correspondent pas.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Adresse email invalide.';
    } else {
        // vérifier si l'utilisateur existe déjà
        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $utilisateur_existant = $stmt->fetch();

        if ($utilisateur_existant) {
            $error_message = 'Cet email est déjà utilisé.';
        } else {
            // hasher le mot de passe
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);

            // insérer le nouvel utilisateur
            $stmt = $pdo->prepare("INSERT INTO utilisateurs (email, mot_de_passe, nom, prenom) VALUES (:email, :mot_de_passe, :nom, :prenom)");

            if ($stmt->execute([
                ':email' => $email,
                ':mot_de_passe' => $password_hashed,
                ':nom' => $nom,
                ':prenom' => $prenom
            ])) {
                $success_message = 'Inscription réussie! Vous pouvez maintenant vous connecter.';
                // réinitialiser le formulaire
                $email = $nom = $prenom = '';
            } else {
                $error_message = 'Erreur lors de l\'inscription. Veuillez réessayer.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-translate-key="title-register">Le Club Famille - Inscription</title>
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
        <div class="user-space">
            <?php if (!is_logged_in()): ?>
                <a href="admin.php" class="btn-login">Espace Admin</a>
            <?php endif; ?>
            <?php if (is_logged_in() && !is_admin()): ?>
                <span style="color: #999; font-size: 14px;">Accès admin réservé</span>
            <?php endif; ?>
            <?php if (is_logged_in()): ?>
                <span>Bienvenue, <?php echo htmlspecialchars(get_logged_user()); ?></span>
                <a href="logout.php" class="btn-login">Déconnexion</a>
            <?php else: ?>
                <a href="login.php" class="btn-login">Connexion</a>
            <?php endif; ?>
        </div>
    </header>

    <nav>
        <ul>
            <li><a href="index.php" data-translate-key="nav-about">A propos</a></li>
            <li><a href="calendrier.php" data-translate-key="nav-calendar">Calendrier</a></li>
            <li><a href="contact.php" data-translate-key="nav-contact">Nous contacter</a></li>
            <?php if (is_admin()): ?>
                <li><a href="admin.php">Admin</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <?php if (!empty($error_message)): ?>
        <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 10px; margin: 20px auto; max-width: 400px; text-align: center; border: 2px solid #f5c6cb;">
            ⚠ <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php elseif (!empty($success_message)): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 10px; margin: 20px auto; max-width: 400px; text-align: center; border: 2px solid #28a745;">
            ✓ <?php echo htmlspecialchars($success_message); ?>
        </div>
        <p style="text-align: center; margin-top: 20px;">
            <a href="login.php" style="color: #E2C6F3; text-decoration: none; font-weight: 600;">Aller à la page de connexion</a>
        </p>
    <?php endif; ?>

    <div class="conteneur_login">
        <h3 data-translate-key="register-title">Inscription</h3>
        <form method="POST" action="register.php">
            <label for="prenom" data-translate-key="register-firstname-label">*Prénom</label>
            <input type="text" id="prenom" name="prenom" placeholder="Prénom" data-translate-key="register-firstname-placeholder" data-translate-attr="placeholder" value="<?php echo htmlspecialchars($prenom ?? ''); ?>">
            <label for="nom" data-translate-key="register-lastname-label">*Nom</label>
            <input type="text" id="nom" name="nom" placeholder="Nom" data-translate-key="register-lastname-placeholder" data-translate-attr="placeholder" value="<?php echo htmlspecialchars($nom ?? ''); ?>">
            <label for="email" data-translate-key="register-email-label">*Email</label>
            <input type="email" id="email" name="email" placeholder="Email" data-translate-key="register-email-placeholder" data-translate-attr="placeholder" required value="<?php echo htmlspecialchars($email ?? ''); ?>">
            <label for="password" data-translate-key="register-password-label">*Mot de passe</label>
            <input type="password" id="password" name="password" placeholder="Mot de passe (minimum 6 caractères)" data-translate-key="register-password-placeholder" data-translate-attr="placeholder" required>
            <label for="password_confirm" data-translate-key="register-password-confirm-label">*Confirmer le mot de passe</label>
            <input type="password" id="password_confirm" name="password_confirm" placeholder="Confirmer le mot de passe" data-translate-key="register-password-confirm-placeholder" data-translate-attr="placeholder" required>
            <input type="submit" value="S'inscrire" data-translate-key="register-submit" data-translate-attr="value">
        </form>
        <p style="text-align: center; margin-top: 20px;" data-translate-key="register-have-account" data-translate-html="true">Vous avez déjà un compte? <a href="login.php" style="color: #232323; text-decoration: none; font-weight: 600;" data-translate-key="register-login-link">Se connecter</a></p>
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
