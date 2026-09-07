<?php
require_once 'config.php';

$ateliers = [];

try {
    $stmt = $pdo->prepare("SELECT id, titre, date_atelier, heure_debut, heure_fin
                           FROM ateliers
                           WHERE date_atelier BETWEEN :debut AND :fin
                           ORDER BY date_atelier, heure_debut");
    $stmt->execute([
        ':debut' => '2026-06-01',
        ':fin' => '2026-06-30'
    ]);

    while ($atelier = $stmt->fetch()) {
        $ateliers[$atelier['date_atelier']][] = $atelier;
    }
} catch (PDOException $e) {
    $ateliers = [];
}

function afficherAteliers($date, $ateliers) {
    if (!isset($ateliers[$date])) {
        return;
    }

    foreach ($ateliers[$date] as $atelier) {
        echo '<a class="atelier" href="inscription_atelier.php?id=' . htmlspecialchars($atelier['id']) . '">';
        echo '<span class="horaire">' . htmlspecialchars(substr($atelier['heure_debut'], 0, 5));

        if (!empty($atelier['heure_fin'])) {
            echo ' - ' . htmlspecialchars(substr($atelier['heure_fin'], 0, 5));
        }

        echo '</span>';
        echo '<span class="nom-activite">' . htmlspecialchars($atelier['titre']) . '</span>';
        echo '</a>';
    }
}

function afficherJour($jour, $date, $ateliers) {
    echo '<td data-date="' . htmlspecialchars($date) . '" id="jour-' . htmlspecialchars($jour) . '">';
    echo '<span class="jour">' . htmlspecialchars($jour) . '</span>';
    echo '<div class="activites">';
    afficherAteliers($date, $ateliers);
    echo '</div>';
    echo '</td>';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-translate-key="title-calendar">Le Club Famille</title>
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
        <div class="conteneur1">
            <p data-translate-key="calendar-intro-1">Chaque mois, nous changeons notre programme d'activités, pour répondre aux besoins exprimés par les bénéficiaires.</p>
            <p data-translate-key="calendar-intro-2">Exemple d'activités :</p>
            <ul>
                <li data-translate-key="calendar-item-1" data-translate-html="true"><b>Ateliers créatifs</b> : nous organisons des ateliers de musique, de photographie, ou encore de création de bandes dessinées pour stimuler l'expression créative, etc.</li>
                <li data-translate-key="calendar-item-2" data-translate-html="true"><b>Bien-être</b> : des séances de sophrologie sont proposées pour initier les personnes accompagnées à la relaxation et à la gestion du stress.</li>
                <li data-translate-key="calendar-item-3" data-translate-html="true"><b>Activités libres</b> : les personnes choisissent leurs propres activités pendant les temps libres, ce qui peut inclure des loisirs personnels, de la lecture, ou des discussions autour de la parentalité.</li>
                <li data-translate-key="calendar-item-4" data-translate-html="true"><b>Promenades en nature</b> : des sorties sont organisées pour permettre aux membres de profiter des bienfaits de la nature, accompagnés par nos compagnons canins, Jérôme, Coffee ou Croc Dur.</li>
            </ul>
        </div>

        <div class="calendrier_janvier" style="width: 98%; max-width: 98%; margin: 0 auto;">


        <h3 style="text-align:center;margin:20px 0 15px;">Calendrier du mois de Juin</h3>
        <p style="text-align:center;margin-top:15px;color:#232323;opacity:0.8;">
            Cliquez sur un atelier pour vous inscrire.
        </p>

        <table class="calendrier" aria-label="Calendrier des ateliers">
            <thead>
                <tr>
                    <th>Lundi</th><th>Mardi</th><th>Mercredi</th><th>Jeudi</th><th>Vendredi</th><th>Samedi</th>
                </tr>

            </thead>
            <tbody>
                <!-- Lignes de jours -->
                <tr>
                    <?php afficherJour(1, '2026-06-01', $ateliers); ?>
                    <?php afficherJour(2, '2026-06-02', $ateliers); ?>
                    <?php afficherJour(3, '2026-06-03', $ateliers); ?>
                    <?php afficherJour(4, '2026-06-04', $ateliers); ?>
                    <?php afficherJour(5, '2026-06-05', $ateliers); ?>
                    <?php afficherJour(6, '2026-06-06', $ateliers); ?>
                </tr>
                <tr>
                    <?php afficherJour(8, '2026-06-08', $ateliers); ?>
                    <?php afficherJour(9, '2026-06-09', $ateliers); ?>
                    <?php afficherJour(10, '2026-06-10', $ateliers); ?>
                    <?php afficherJour(11, '2026-06-11', $ateliers); ?>
                    <?php afficherJour(12, '2026-06-12', $ateliers); ?>
                    <?php afficherJour(13, '2026-06-13', $ateliers); ?>
                </tr>
                <tr>
                    <?php afficherJour(15, '2026-06-15', $ateliers); ?>
                    <?php afficherJour(16, '2026-06-16', $ateliers); ?>
                    <?php afficherJour(17, '2026-06-17', $ateliers); ?>
                    <?php afficherJour(18, '2026-06-18', $ateliers); ?>
                    <?php afficherJour(19, '2026-06-19', $ateliers); ?>
                    <?php afficherJour(20, '2026-06-20', $ateliers); ?>
                </tr>
                <tr>
                    <?php afficherJour(22, '2026-06-22', $ateliers); ?>
                    <?php afficherJour(23, '2026-06-23', $ateliers); ?>
                    <?php afficherJour(24, '2026-06-24', $ateliers); ?>
                    <?php afficherJour(25, '2026-06-25', $ateliers); ?>
                    <?php afficherJour(26, '2026-06-26', $ateliers); ?>
                    <?php afficherJour(27, '2026-06-27', $ateliers); ?>
                </tr>
                <tr>
                    <?php afficherJour(29, '2026-06-29', $ateliers); ?>
                    <?php afficherJour(30, '2026-06-30', $ateliers); ?>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

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
