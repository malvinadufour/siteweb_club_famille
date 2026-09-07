<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-translate-key="title-home">Le Club Famille</title>
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
            <li><a href="index.php" class="active" data-translate-key="nav-about">A propos</a></li>
            <li><a href="calendrier.php" data-translate-key="nav-calendar">Calendrier</a></li>
            <li><a href="contact.php" data-translate-key="nav-contact">Nous contacter</a></li>
            <?php if (is_admin()): ?>
                <li><a href="admin.php">Admin</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="conteneur1">
        <p data-translate-key="home-intro-1">Initié par l'<b>association L'Amitié à Reims</b> et soutenu par l'ARS Grand Est, le Club Famille a été lancé en mars 2023 dans le but de créer un espace
             de ressources et de partage pour un public spécifique : les jeunes aidants et leurs parents présentant des troubles psychiatriques.</p>
        <p data-translate-key="home-intro-2">Notre initiative vise à répondre à un besoin peu pris en compte de soutien à la parentalité pour les parents suivis en psychiatrie,
            axé sur le développement de compétences psychosociales, les interactions sociales entre pairs et les facteurs de protection et de bien-être des enfants.
            Nous nous concentrons sur l'accompagnement, l'éducation, la prévention et le soutien par des moyens non cliniques et parfois en complément du parcours de soin traditionnel ;
            Nous ne sommes pas un service de soin mais de "prendre soin".</p>
    </div>

    <h2 data-translate-key="home-missions-title"><b>Nos Missions</b></h2>
    <div class="parent">
        <div class="conteneur2">
            <h3 data-translate-key="home-support-young-title">Soutien aux jeunes aidants</h3>
            <p data-translate-key="home-support-young-text">Les jeunes aidants ont souvent des responsabilités supplémentaires : ils gèrent leurs propres vies et aident en même temps un membre de la famille malade ou handicapé
                dans leur quotidien. Reconnaissant la complexité de leur rôle, le Club Famille s'engage à leur offrir un lieu de soutien, d'écoute, d'information et de partage d'expériences.</p>
        </div>
        <div class="conteneur3">
            <h3 data-translate-key="home-parental-support-title">Accompagnement à la parentalité</h3>
            <p data-translate-key="home-parental-support-text">Nous apportons aux parents un espace de convivialité où ils peuvent aborder leur difficulté parentale. Nos ateliers, groupes de soutien et ressources mis en place créent une
                communauté de soutien et d'échange.</p>
        </div>
    </div>

    <div class="conteneur4">
        <h3 data-translate-key="home-team-title">L'équipe du Club Famille</h3>
        <ul>
            <li data-translate-key="home-team-item-1">Une Cheffe de Service</li>
            <li data-translate-key="home-team-item-2">Une Infirmière diplômée d'État</li>
            <li data-translate-key="home-team-item-3">Un Moniteur Éducateur</li>
            <li data-translate-key="home-team-item-4">Une Secrétaire</li>
        </ul>
    </div>

    <div class="conteneur5">
        <h3 data-translate-key="home-program-title">Programme « Ambassadeurs Santé Mentale »</h3>
        <p data-translate-key="home-program-text" data-translate-html="true">Co-supervision avec <b>Unis-Cité</b> : Ensemble, nous mettons en œuvre le programme « Ambassadeurs Santé Mentale », une initiative nationale visant à former des jeunes volontaires
         en service civique afin qu'ils deviennent des acteurs de la sensibilisation à la santé mentale, le soutien par les pairs et la déstigmatisation. Ce programme comprend :</p>
        <ul>
            <li data-translate-key="home-program-item-1" data-translate-html="true"><b>La formation des jeunes</b> : Les jeunes volontaires sont formés pour comprendre les enjeux de la santé mentale, reconnaître les signes de détresse psychologique 
                et apporter un soutien de premier niveau à leurs pairs.</li>
            <li data-translate-key="home-program-item-2" data-translate-html="true"><b>Des actions de sensibilisation</b> : Les ambassadeurs mènent des actions de sensibilisation dans divers contextes, y compris les écoles, les universités, et au sein de la communauté,
                 pour briser les tabous et promouvoir une meilleure compréhension de la santé mentale.</li>
            <li data-translate-key="home-program-item-3" data-translate-html="true"><b>Support par les pairs</b> : En tant qu'ambassadeurs, ils offrent un soutien aux jeunes Rémois, créant ainsi un environnement plus inclusif et empathique pour tous.</li>
        </ul>
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
