<section class="auth">
    <div class="auth__content">
        <h1><?= $pageType === 'login' ? "Connexion" : "Inscription" ?></h1>

        <form action="" method="POST">
            <?php if ($pageType === 'register'): ?>
                <div class="input-group">
                    <label for="username">Pseudo</label>
                    <input type="text" id="username" name="username" required>
                </div>
            <?php endif; ?>

            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="input-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn--primary">
                <?= $pageType === 'login' ? "Se connecter" : "S'inscrire" ?>
            </button>
        </form>

        <p>
            <?php 
            if ($pageType === 'login') {
                echo "Pas encore de compte ? <a href=\"" . action_url("inscription") . "\">Inscrivez-vous</a>";
            } else {
                echo "Déjà un compte ? <a href=\"" . action_url("connexion") . "\">Connectez-vous</a>";
            } ?>
        </p>
    </div>

    <div class="auth__picture">
        <img src="<?= img_url("auth_picture.webp") ?>" alt="Photo d'une bibliothèque pleine à craquer">
    </div>
</section>
