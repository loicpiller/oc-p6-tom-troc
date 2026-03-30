<div id="main-container">
    <img class="book-cover" src="<?= $book->getImage() ? img_url($book->getImage()) : img_url("default_book_picture.jpg"); ?>">

    <section class="book-infos">
        <h1><?= htmlspecialchars($book->getTitle()); ?></h1>
        <span class="book-author">par <?= htmlspecialchars((string)$book->getAuthor()); ?></span>

        <div class="book-desc">
            <h2>Description</h2>
            <p><?= htmlspecialchars((string)$book->getDescription()); ?></p>
        </div>

        <div class="book-user">
            <h2>Propriétaire</h2>
            <a class="user-card" href="<?= action_url('profile/{id}', ['id' => $book->getUser()->getId()]); ?>">
                <div class="user-card-avatar">
                    <img src="<?= img_url($book->getUser()->getAvatar()); ?>">
                </div>
                <span><?= htmlspecialchars($book->getUser()->getUsername()); ?></span>
            </a>
        </div>

        <a class="btn btn--primary" href="<?= action_url('messages/{contactId}', ['contactId' => $book->getUser()->getId()]); ?>">Envoyer un message</a>

    </section>
</div>
