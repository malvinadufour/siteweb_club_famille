<?php
require_once 'config.php';

$error_message = '';
$is_admin_login_context = (($_GET['from'] ?? $_POST['from'] ?? '') === 'admin');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = clean_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // validation
    if (empty($email) || empty($password)) {
        $error_message = 'Veuillez entrer votre email et votre mot de passe.';
    } else {
        // chercher l'utilisateur
        $stmt = $pdo->prepare("SELECT id, mot_de_passe, nom, prenom FROM utilisateurs WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user) {
            // vérifier le mot de passe
            if (password_verify($password, $user['mot_de_passe'])) {
                if ($is_admin_login_context && !in_array($email, ADMIN_EMAILS, true)) {
                    $error_message = 'Seuls les administrateurs du site sont autorisés à accéder à cet espace. Veuillez vous connecter via le bouton "Connexion".';
                } else {
                    // connexion réussie
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['email'] = $email;
                    $_SESSION['nom'] = $user['nom'];
                    $_SESSION['prenom'] = $user['prenom'];
                    $_SESSION['username'] = $user['prenom'] . ' ' . $user['nom'];
                    $_SESSION['is_admin'] = in_array($email, ADMIN_EMAILS, true);

                    // enregistrer l'activité
                    log_activity($user['id'], 'Connexion', 'Utilisateur connecté');

                    // rediriger l'administrateur vers son espace
                    header('Location: ' . ($_SESSION['is_admin'] ? 'admin.php' : 'index.php'));
                    exit();
                }
            } else {
                $error_message = 'Mot de passe incorrect.';
            }
        } else {
            $error_message = 'Email non trouvé.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-translate-key="title-login">Le Club Famille - Connexion</title>
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
                    <a href="admin.php" class="btn-login<?php echo $is_admin_login_context ? ' active' : ''; ?>">Espace Admin</a>
                <?php endif; ?>
                <?php if (is_logged_in() && !is_admin()): ?>
                    <span style="color: #999; font-size: 14px;">Accès admin réservé</span>
                <?php endif; ?>
                <?php if (is_logged_in()): ?>
                    <a href="logout.php" class="btn-login">Déconnexion</a>
                <?php else: ?>
                    <a href="login.php" class="btn-login<?php echo !$is_admin_login_context ? ' active' : ''; ?>">Connexion</a>
                <?php endif; ?>
            </div>
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
    <?php endif; ?>

    <div class="conteneur_login">
        <h3 data-translate-key="login-title">Se connecter</h3>
        <p data-translate-key="login-required-note" style="margin-bottom: 15px; color: #232323; font-size: 14px;">Les champs marqués d’un astérisque (*) sont obligatoires.</p>
        <form method="POST" action="login.php">
            <?php if ($is_admin_login_context): ?>
                <input type="hidden" name="from" value="admin">
            <?php endif; ?>
            <label for="email" data-translate-key="login-email-label">*Email</label>
            <input type="email" id="email" name="email" placeholder="Email" data-translate-key="login-email-placeholder" data-translate-attr="placeholder" required>
            <label for="password" data-translate-key="login-password-label">*Mot de passe</label>
            <input type="password" id="password" name="password" placeholder="Mot de passe" data-translate-key="login-password-placeholder" data-translate-attr="placeholder" required>
            <input type="submit" value="Connexion" data-translate-key="login-submit" data-translate-attr="value">
        </form>
        <?php if (!$is_admin_login_context): ?>
            <p style="text-align: center; margin-top: 20px;" data-translate-key="login-register-note" data-translate-html="true">Pas encore inscrit? <a href="register.php" style="color: #232323; text-decoration: none; font-weight: 600;" data-translate-key="login-register-link">Créer un compte</a></p>
        <?php endif; ?>
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
