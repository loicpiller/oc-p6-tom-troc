<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $title; ?></title>
        <?php foreach ($styles as $style): ?>
        <link rel="stylesheet" href="<?= "/public/css/$style.css" ?>">
        <?php endforeach; ?>
    </head>
    <body>
        <header>
            <img src="<?= img_url("logo/main_logo.svg") ?>" alt="Logo Tom Troc sans texte" />
            <nav id="main-nav" aria-label="Navigation principale">
                <a href="<?= action_url('/') ?>">Accueil</a>
                <a href="">Nos livres à l'échange</a>
            </nav>
            <nav id="user-actions-nav" aria-label="Navigation pour les actions utilisateur">
                <a href=""><img src="<?= img_url("icons/messaging.svg") ?>" alt="Icon de la messagerie" />Messagerie</a>
                <a href=""><img src="<?= img_url("icons/account.svg") ?>" alt="Icon de l'accès au compte utilisateur" />Mon compte</a>
                <a href="<?= action_url("connexion") ?>">Connexion</a>
            </nav>
        </header>
        <main>
            <?php echo $content; // Here we display the page content ?>
        </main>
        <footer>
            <p><a href="#">Politique de confidentialité</a></p>
            <p><a href="#">Mentions légales</a></p>
            <p>Tom Troc©</p>
            <img src="<?= img_url("logo/min_logo.svg") ?>" alt="Logo Tom Troc sans texte" />
        </footer>
    </body>
</html>
<?php
