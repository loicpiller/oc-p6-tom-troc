<section class="join-our-readers">
    <div class="container">
        <div class="text-container">
            <h1>Rejoignez nos lecteurs passionnés</h1>
            <p>Donnez une nouvelle vie à vos livres en les échangeant avec d'autres amoureux de la lecture. Nous croyons en la magie du partage de connaissances et d'histoires à travers les livres.</p>
            <a class="btn btn--primary" href="<?= action_url("inscription"); ?>">Découvrir</a>
        </div>
        <div class="img-container">
            <img src="<?= img_url("home_page/hamza.jpg"); ?>" />
            <span>Hamza</span>
        </div>
    </div>
</section>

<section class="last-added-books">
    <div class="container">
        <h2>Les derniers livres ajoutés</h2>

        <div class="books-cards-container">
            <?php foreach ($books as $book): ?>
                <a href="<?= action_url('livre/{id}', ['id' => $book->getId()]); ?>" class="book-card">
                    <div class="book-card-cover">
                        <img src="<?= $book->getImage() ? img_url($book->getImage()) : img_url("default_book_picture.jpg"); ?>">
                    </div>
                    <div class="card-body">
                        <span class="title"><?= htmlspecialchars($book->getTitle()); ?></span>
                        <span class="author"><?= htmlspecialchars((string)$book->getAuthor()); ?></span>
                        <span class="user">Vendu par : <?= htmlspecialchars($book->getUser()->getUsername()); ?></span>
                    </div>
                </a>
            <?php endforeach ?>
        </div>

        <a class="btn btn--primary" href="<?= action_url("livres-echangeables"); ?>">Voir tous les livres</a>
    </div>
</section>

<section class="how-it-works">
    <div class="container">
        <h2>Comment ça marche ?</h2>
        <p class="subtitle">Échanger des livres avec TomTroc c'est simple et amusant ! Suivez ces étapes pour commencer :</p>
        <div class="steps">
            <div class="step">
                <p>Inscrivez-vous gratuitement sur notre plateforme.</p>
            </div>
            <div class="step">
                <p>Ajoutez les livres que vous souhaitez échanger à votre profil.</p>
            </div>
            <div class="step">
                <p>Parcourez les livres disponibles chez d'autres membres.</p>
            </div>
            <div class="step">
                <p>Proposez un échange et discutez avec d'autres passionnés de lecture.</p>
            </div>
        </div>
        <a class="btn btn--secondary" href="<?= action_url("livres-echangeables"); ?>">Voir tous les livres</a>
    </div>
</section>

<img class="banner" src="<?= img_url("home_page/banner.png"); ?>">

<section class="our-values">
    <div class="container">
        <h2>Nos valeurs</h2>
        <p>Chez Tom Troc, nous mettons l'accent sur le partage, la découverte et la communauté. Nos valeurs sont ancrées dans notre passion pour les livres et notre désir de créer des liens entre les lecteurs. Nous croyons en la puissance des histoires pour rassembler les gens et inspirer des conversations enrichissantes.</p>
        <p>Notre association a été fondée avec une conviction profonde : chaque livre mérite d'être lu et partagé.</p>
        <p>Nous sommes passionnés par la création d'une plateforme conviviale qui permet aux lecteurs de se connecter, de partager leurs découvertes littéraires et d'échanger des livres qui attendent patiemment sur les étagères.</p>
        <span>L’équipe Tom Troc</span>
        <img src="<?= img_url("home_page/heart.svg") ?>">
    </div>
</section>
